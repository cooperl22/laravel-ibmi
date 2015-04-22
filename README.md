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
- [Registering the Package](#registering-the-package)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation

Add laravel-ibmi to your composer.json file:

```
"require": {
    "cooperl/laravel-ibmi": "~1.0"
}
```

Use [composer](http://getcomposer.org) to install this package.

```
$ composer update
```

### Registering the Package

Add the laravel-db2 and laravel-ibmi Service Providers to your config in ``app/config/app.php``:

```php
'providers' => [
    'Cooperl\Database\DB2\DB2ServiceProvider',
    'Cooperl\IBMi\IBMiServiceProvider',
],
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

    'odbc' => [
        'driver'         => 'odbc',
        'host'           => '',
        'database'       => '',
        'username'       => '',
        'password'       => '',
        'charset'        => 'utf8',
        'ccsid'          => 1208,
        'prefix'         => '',
        'schema'         => '',
        'i5_libl'        => '',
        'i5_lib'         => '',
        'i5_commit'      => 0,
        'i5_naming'      => 0,
        'i5_date_fmt'    => 5,
        'i5_date_sep'    => 0,
        'i5_decimal_sep' => 0,
        'i5_time_fmt'    => 0,
        'i5_time_sep'    => 0,
        'options'  => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
            ]
    ],

    'ibm' => [
        'driver'         => 'ibm',
        'host'           => '',
        'database'       => '',
        'username'       => '',
        'password'       => '',
        'charset'        => 'utf8',
        'ccsid'          => 1208,
        'prefix'         => '',
        'schema'         => '',
        'i5_libl'        => '',
        'i5_lib'         => '',
        'i5_commit'      => 0,
        'i5_naming'      => 0,
        'i5_date_fmt'    => 5,
        'i5_date_sep'    => 0,
        'i5_decimal_sep' => 0,
        'i5_time_fmt'    => 0,
        'i5_time_sep'    => 0,
        'options'  => [
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false
            ]
    ],

```
driver setting is either 'odbc' for ODBC connection or 'ibm' for pdo_ibm connection
Then if driver is 'odbc', database must be set to ODBC connection name.
if driver is 'ibm', database must be set to IBMi database name (WRKRDBDIRE).

#### Option 2: Configure DB2 using package config file

Run on the command line from the root of your project:

```
$ php artisan config:publish cooperl/laravel-db2
```

Set your laravel-db2 credentials in ``app/config/packages/cooperl/laravel-db2/config.php``
the same way as above


## Usage

#### Database usage

consult the [Laravel framework documentation](http://laravel.com/docs).

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

for more details please consult [PHP XMLSERVICE Toolkit documentation](http://files.zend.com/help/Zend-Server-IBMi/zend-server.htm#php_toolkit_xml_service_functions.htm).
