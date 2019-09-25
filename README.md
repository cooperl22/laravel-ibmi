# laravel-ibmi for Laravel 6.0

Please see for full instructions the original project https://github.com/cooperl22/laravel-db2. I made this project in order to support Laravel 6.0 and will most likely abandon this project when upstream is compatible with Laravel 6.0.

# What is changed
* Depencies in composer.json, which would prevent the installation
* array_get is obsolete in Laravel 6.0, Changed this to Arr:: get

# Using this project
Add these two repositories to composer.json
```  
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/wjonkerhulst/laravel-db2"
    },{
        "type": "vcs",
        "url": "https://github.com/wjonkerhulst/laravel-ibmi"
    }
]
```
And add these packages to composer.json
```
"illuminate/database": "^6.0",
"zendtech/IbmiToolkit": "^1.7.0",
"cooperl/laravel-db2": "dev-master",
"cooperl/laravel-ibmi": "dev-master"
```
