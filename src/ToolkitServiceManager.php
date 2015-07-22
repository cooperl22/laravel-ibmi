<?php
namespace Cooperl\IBMi;

use ToolkitService;

class ToolkitServiceManager
{

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The active connection instances.
     *
     * @var array
     */
    protected $connections = [];

    /**
     * Create a new toolkit manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

        /**
     * Get a toolkit connection instance.
     *
     * @param  string  $name
     * @return \ToolkitApi\ToolkitService
     */
    public function connection($name = null)
    {
        // If we haven't created this connection, we'll create it based on the config
        // provided in the application. Once we've created the connections we will
        // set the "fetch mode" for PDO which determines the query return types.
        if ( ! isset($this->connections[$name]))
        {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Make the toolkit connection instance.
     *
     * @param  string  $name
     * @return \ToolkitApi\ToolkitService
     */
    protected function makeConnection($name)
    {
        $config = $this->getConfig($name);

        $transportType = '';
        $dsn = '';
        switch ($config['driver']) {
            case 'odbc':
                $transportType = 'odbc';
                $dsn = $this->getDsn($config);
                break;
            case 'ibm':
                $transportType = 'ibm_db2';
                $dsn = $config["database"];
                break;
            default:
                break;
        }
        $isPersistent = !array_key_exists(\PDO::ATTR_PERSISTENT, $config["options"]) ?: $config["options"][\PDO::ATTR_PERSISTENT];

        return ToolkitService::getInstance($dsn, $config["username"], $config["password"], $transportType, $isPersistent);
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        // To get the database connection configuration, we will just pull each of the
        // connection configurations and get the configurations for the given name.
        // If the configuration doesn't exist, we'll throw an exception and bail.
        $connections = $this->app['config']['database.connections'];

        if (is_null($config = array_get($connections, $name)))
        {
            throw new \InvalidArgumentException("Database [$name] not configured.");
        }

        return $config;
    }

    protected function getDsn(array $config) {
        extract($config);

        $dsn = // General settings
               "DRIVER={iSeries Access ODBC Driver};"
             . "SYSTEM=$host;"
             . "UserID=$username;"
             . "Password=$password;"
             //Server settings
             . "DATABASE=$database;"
             . "SIGNON=$signon;"
             . "SSL=$ssl;"
             . "CommitMode=$commitMode;"
             . "ConnectionType=$connectionType;"
             . "DefaultLibraries=$defaultLibraries;"
             . "Naming=$naming;"
             . "UNICODESQL=$unicodeSql;"
             // Format settings
             . "DateFormat=$dateFormat;"
             . "DateSeperator=$dateSeperator;"
             . "Decimal=$decimal;"
             . "TimeFormat=$timeFormat;"
             . "TimeSeparator=$timeSeparator;"
             // Performances settings
             . "BLOCKFETCH=$blockFetch;"
             . "BlockSizeKB=$blockSizeKB;"
             . "AllowDataCompression=$allowDataCompression;"
             . "CONCURRENCY=$concurrency;"
             . "LAZYCLOSE=$lazyClose;"
             . "MaxFieldLength=$maxFieldLength;"
             . "PREFETCH=$prefetch;"
             . "QUERYTIMEOUT=$queryTimeout;"
             // Modules settings
             . "DefaultPkgLibrary=$defaultPkgLibrary;"
             . "DefaultPackage=$defaultPackage;"
             . "ExtendedDynamic=$extendedDynamic;"
             // Diagnostic settings
             . "QAQQINILibrary=$QAQQINILibrary;"
             . "SQDIAGCODE=$sqDiagCode;"
             // Sort settings
             . "LANGUAGEID=$languageId;"
             . "SORTTABLE=$sortTable;"
             . "SortSequence=$sortSequence;"
             . "SORTWEIGHT=$sortWeight;"
             // Conversion settings
             . "AllowUnsupportedChar=$allowUnsupportedChar;"
             . "CCSID=$ccsid;"
             . "GRAPHIC=$graphic;"
             . "ForceTranslation=$forceTranslation;"
             // Other settings
             . "ALLOWPROCCALLS=$allowProcCalls;"
             . "DB2SQLSTATES=$DB2SqlStates;"
             . "DEBUG=$debug;"
             . "TRUEAUTOCOMMIT=$trueAutoCommit;"
             . "CATALOGOPTIONS=$catalogOptions;"
             . "LibraryView=$libraryView;"
             . "ODBCRemarks=$ODBCRemarks;"
             . "SEARCHPATTERN=$searchPattern;"
             . "TranslationDLL=$translationDLL;"
             . "TranslationOption=$translationOption;"
             . "MAXTRACESIZE=$maxTraceSize;"
             . "MultipleTraceFiles=$multipleTraceFiles;"
             . "TRACE=$trace;"
             . "TRACEFILENAME=$traceFilename;"
             . "ExtendedColInfo=$extendedColInfo;"
             ;

        return $dsn;
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['database.default'];
    }

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->app['config']['database.default'] = $name;
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }
    
}
