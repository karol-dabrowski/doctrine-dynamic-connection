# Doctrine Dynamic Connection
[![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/karol-dabrowski/doctrine-dynamic-connection/Tests/master?label=Tests)](https://github.com/karol-dabrowski/doctrine-dynamic-connection/actions/workflows/tests.yml?query=branch%3Amaster)
[![License: MIT](https://img.shields.io/packagist/l/karol-dabrowski/doctrine-dynamic-connection)](https://github.com/karol-dabrowski/doctrine-dynamic-connection/blob/master/LICENSE.md)

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

### Author
Karol Dabrowski [@kdabrowskidev](https://twitter.com/kdabrowskidev)

### License
Released under the [MIT license](https://github.com/karol-dabrowski/doctrine-dynamic-connection/blob/master/LICENSE.md).
