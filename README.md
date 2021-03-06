# Doctrine Dynamic Connection
[![Packagist Version](https://img.shields.io/packagist/v/karol-dabrowski/doctrine-dynamic-connection?label=Version)](https://packagist.org/packages/karol-dabrowski/doctrine-dynamic-connection)
[![GitHub Workflow Status - Tests](https://img.shields.io/github/workflow/status/karol-dabrowski/doctrine-dynamic-connection/Tests/master?label=Tests)](https://github.com/karol-dabrowski/doctrine-dynamic-connection/actions/workflows/tests.yml?query=branch%3Amaster)
[![GitHub Workflow Status - code analysis](https://img.shields.io/github/workflow/status/karol-dabrowski/doctrine-dynamic-connection/Code%20analysis/master?label=Code%20analysis)](https://github.com/karol-dabrowski/doctrine-dynamic-connection/actions/workflows/code_analysis.yml?query=branch%3Amaster)
[![License: MIT](https://img.shields.io/packagist/l/karol-dabrowski/doctrine-dynamic-connection?label=License)](https://github.com/karol-dabrowski/doctrine-dynamic-connection/blob/master/LICENSE.md)

### Requirements

* PHP 7.3 or greater

### Installation
To install Doctrine Dynamic Connection via Composer execute the following command:
``` bash
composer require karol-dabrowski/doctrine-dynamic-connection
```
### Setup
To use the library features, you need to make two changes comparing to the basic [Doctrine ORM configuration](https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/tutorials/getting-started.html):
1. Add `wrapperClass` parameter with the name of `DynamicConnectionWrapper` class as a value to the array of database connection parameters. For simplicity, use `::class` keyword. 
2. Create and instance of `DynamicEntityManager` and pass your default entity manager as a constructor argument.
```php
<?php
// bootstrap.php
require_once 'vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use DynamicConnection\DynamicConnectionWrapper;
use DynamicConnection\DynamicEntityManager;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'pass',
    'dbname'   => 'db_name',
    'wrapperClass' => DynamicConnectionWrapper::class
);

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

$config = Setup::createAnnotationMetadataConfiguration(
    array(__DIR__."/src"),
    $isDevMode,
    $proxyDir,
    $cache,
    $useSimpleAnnotationReader
);

// For XML mappings
// $config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);

// For YAML mappings
// $config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);

$entityManager = EntityManager::create($dbParams, $config);
$dynamicEntityManager = new DynamicEntityManager($entityManager);
```

### Usage
The most important method of `DynamicEntityManager` is `modifyConnection()`. It takes five parameters, but none of them is required.
```php
public function modifyConnection(
    ?string $databaseName = null,
    ?string $username = null,
    ?string $password = null,
    ?string $host = null,
    ?string $port = null
): void;
```
Only parameters with non-null value will be modified. If you call the method with no arguments, your connection parameters will not be changed. Pass `null` when you don't want to change a particular parameter.
```php
<?php

// Change database name
$dynamicEntityManager->modifyConnection('new_db_name');

// Change database name and database user
$dynamicEntityManager->modifyConnection('new_db_name', 'username', 'password');

// Change database user and leave database name unchanged
$dynamicEntityManager->modifyConnection(null, 'username', 'password');

// Change only database host and port, leave database name and user unchanged
$dynamicEntityManager->modifyConnection(null, null, null, '127.0.0.2', '3307');

// Change database name, host and port, leave database user unchanged
$dynamicEntityManager->modifyConnection('new_db_name', null, null, '127.0.0.2', '3307');

// Change all parameters
$dynamicEntityManager->modifyConnection('new_db_name', 'username', 'password', '127.0.0.2', '3307');
```

### Author
Karol Dabrowski [@kdabrowskidev](https://twitter.com/kdabrowskidev)

### License
Released under the [MIT license](https://github.com/karol-dabrowski/doctrine-dynamic-connection/blob/master/LICENSE.md).
