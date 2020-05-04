# laravel-ibmi

[![Latest Stable Version](https://poser.pugx.org/cooperl/laravel-ibmi/v/stable)](https://packagist.org/packages/cooperl/laravel-ibmi)
[![Total Downloads](https://poser.pugx.org/cooperl/laravel-ibmi/downloads)](https://packagist.org/packages/cooperl/laravel-ibmi)
[![Latest Unstable Version](https://poser.pugx.org/cooperl/laravel-ibmi/v/unstable)](https://packagist.org/packages/cooperl/laravel-ibmi)
[![License](https://poser.pugx.org/cooperl/laravel-ibmi/license)](https://packagist.org/packages/cooperl/laravel-ibmi)

laravel-ibmi is a simple DB2 & Toolkit for IBMi service provider for Laravel.
It provides DB2 Connection by extending the Illuminate Database component of the laravel framework.
Plus it also provides Toolkit for IBMi so that you can access IBMi resources with same credentials.

---

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation
Add laravel-ibmi to your composer.json file:
```
"require": {
    "cooperl/laravel-ibmi": "^7.0"
}
```
Use [composer](https://getcomposer.org) to install this package.
```
$ composer update
```

### Configuration
There are two ways to configure laravel-ibmi. You can choose the most convenient way for you. You can put your DB2 credentials into ``app/config/database.php`` (option 1) file or use package config file which you can be generated through command line by artisan (option 2).

#### Option 1: Configure DB2 using ``app/config/database.php`` file

Simply add this code at the end of your ``app/config/database.php`` file:

```php
    /*
    |--------------------------------------------------------------------------
    | DB2 Databases
    |--------------------------------------------------------------------------
    */

    'ibmi' => [
        'driver' => 'db2_ibmi_odbc',
        // or 'db2_ibmi_ibm' / 'db2_zos_odbc' / 'db2_expressc_odbc
        'driverName' => '{IBM i Access ODBC Driver}',
        // or '{iSeries Access ODBC Driver}' / '{IBM i Access ODBC Driver 64-bit}'
        'host' => 'server',
        'username' => '',
        'password' => '',
        'database' => 'WRKRDBDIRE entry',
        'prefix' => '',
        'schema' => 'default schema',
        'port' => 50000,
        'date_format' => 'Y-m-d H:i:s',
        // or 'Y-m-d H:i:s.u' / 'Y-m-d-H.i.s.u'...
        'odbc_keywords' => [
            'SIGNON' => 3,
            'SSL' => 0,
            'CommitMode' => 2,
            'ConnectionType' => 0,
            'DefaultLibraries' => '',
            'Naming' => 0,
            'UNICODESQL' => 0,
            'DateFormat' => 5,
            'DateSeperator' => 0,
            'Decimal' => 0,
            'TimeFormat' => 0,
            'TimeSeparator' => 0,
            'TimestampFormat' => 0,
            'ConvertDateTimeToChar' => 0,
            'BLOCKFETCH' => 1,
            'BlockSizeKB' => 32,
            'AllowDataCompression' => 1,
            'CONCURRENCY' => 0,
            'LAZYCLOSE' => 0,
            'MaxFieldLength' => 15360,
            'PREFETCH' => 0,
            'QUERYTIMEOUT' => 1,
            'DefaultPkgLibrary' => 'QGPL',
            'DefaultPackage' => 'A /DEFAULT(IBM),2,0,1,0',
            'ExtendedDynamic' => 0,
            'QAQQINILibrary' => '',
            'SQDIAGCODE' => '',
            'LANGUAGEID' => 'ENU',
            'SORTTABLE' => '',
            'SortSequence' => 0,
            'SORTWEIGHT' => 0,
            'AllowUnsupportedChar' => 0,
            'CCSID' => 819,
            'GRAPHIC' => 0,
            'ForceTranslation' => 0,
            'ALLOWPROCCALLS' => 0,
            'DB2SQLSTATES' => 0,
            'DEBUG' => 0,
            'TRUEAUTOCOMMIT' => 0,
            'CATALOGOPTIONS' => 3,
            'LibraryView' => 0,
            'ODBCRemarks' => 0,
            'SEARCHPATTERN' => 1,
            'TranslationDLL' => '',
            'TranslationOption' => 0,
            'MAXTRACESIZE' => 0,
            'MultipleTraceFiles' => 1,
            'TRACE' => 0,
            'TRACEFILENAME' => '',
            'ExtendedColInfo' => 0,
        ],
        'options' => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_PERSISTENT => false
        ]
        + (defined('PDO::I5_ATTR_DBC_SYS_NAMING') ? [PDO::I5_ATTI5_ATTR_DBC_SYS_NAMINGR_COMMIT => false] : [])
        + (defined('PDO::I5_ATTR_COMMIT') ? [PDO::I5_ATTR_COMMIT => PDO::I5_TXN_NO_COMMIT] : [])
        + (defined('PDO::I5_ATTR_JOB_SORT') ? [PDO::I5_ATTR_JOB_SORT => false] : [])
        + (defined('PDO::I5_ATTR_DBC_LIBL') ? [PDO::I5_ATTR_DBC_LIBL => ''] : [])
        + (defined('PDO::I5_ATTR_DBC_CURLIB') ? [PDO::I5_ATTR_DBC_CURLIB => ''] : []),
        'toolkit' => [
            'sbmjobParams' => 'ZENDPHP7/ZSVR_JOBD/XTOOLKIT',
            'XMLServiceLib' => 'ZENDPHP7',
            'debug' => false,
            'debugLogFile' => storage_path('logs / toolkit_gigc . log'),
            'InternalKey' => ' / tmp / ' . 'Toolkit_' . env('APP_ENV') . '_' . random_int(1, 10),
            'stateless' => false,
            'plugSize' => '512K',
            'encoding' => "UTF-8",
            'ccsidBefore' => "819/1147",
            'ccsidAfter' => "1147/1208",
            'useHex' => true
        ],
    ],

```
driver setting can be:
- 'db2_ibmi_odbc' for IBMi ODBC connection
- 'db2_ibmi_ibm' for IBMi PDO_IBM connection
- 'db2_zos_odbc' for zOS ODBC connection
- 'db2_expressc_odbc for Express-C ODBC connection

Then if driver is 'db2_*_odbc', database must be set to ODBC connection name.
if driver is 'db2_ibmi_ibm', database must be set to IBMi database name (WRKRDBDIRE).

#### Option 2: Configure DB2 using package config file

Run on the command line from the root of your project:

```
$ php artisan vendor:publish
```

Set your laravel-db2 credentials in ``app/config/db2.php``
the same way as above

## Usage

#### Database usage

consult the [Laravel framework documentation](https://laravel.com/docs).

#### Toolkit for IBMi usage :

This package ships with a facade called ``TS`` for ToolkitService which is the name of the main class.

here is an example of how to use this facade:

```php
    $param[] = TS::AddParameterChar('both', 10, 'InventoryCode', 'code', $code);
    $param[] = TS::AddParameterChar('both', 10, 'Description', 'desc', $desc);
    $result = TS::PgmCall("COMMONPGM", "ZENDSVR", $param, null, null);
    if($result)
    {
        var_dump($result['io_param']);
    }
    else
    {
        echo "Execution failed.";
    }
```

If you want to choose another connection than the default one just do this:

```php
    $param[] = TS::connection('other_connection')->AddParameterChar('both', 10, 'InventoryCode', 'code', $code);
    $param[] = TS::connection('other_connection')->AddParameterChar('both', 10, 'Description', 'desc', $desc);
    $result = TS::connection('other_connection')->PgmCall("COMMONPGM", "ZENDSVR", $param, null, null);
    if($result)
    {
        var_dump($result['io_param']);
    }
    else
    {
        echo "Execution failed.";
    }
```

for more details please consult [PHP XMLSERVICE Toolkit documentation](https://docs.roguewave.com/en/zend/current/content/php_toolkit_xml_service_functions.htm).
