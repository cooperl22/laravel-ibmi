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
        switch ($config['driver']) {
            case 'odbc':
                $transportType = 'odbc';
                break;
            case 'ibm':
                $transportType = 'ibm_db2';
                break;
            default:
                break;
        }
        $isPersistent = !array_key_exists(\PDO::ATTR_PERSISTENT, $config["options"]) ?: $config["options"][\PDO::ATTR_PERSISTENT];

        return ToolkitService::getInstance($config["database"], $config["username"], $config["password"], $transportType, $isPersistent);      
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
