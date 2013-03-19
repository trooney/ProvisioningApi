Provisioning API
=================

Unified provisioning interface for Bridgewater AAA, StarACS and Lucent Feature Server interfaces. Uses Lithium PHP framework.


Autoloading
-----------

Composer handles the creation of this project's autoloader.

```php
<?php
// When in the project root
require_once __DIR__ . '/../libraries/autoload.php';
```


Bridgewater
-----------

```php
<?php
use NRC\ProvisioningApi\Api\Bridgewater;

// Create API Object
$api = new Bridgewater(array(
	'host' => 'localhost',
	'port' => 8080,
	'path' => '/api',
	'username' => 'username',
	'password' => 'password',
	'organization' => 'Example Co',
	'domain' => 'example.com',
));


// Create user
$bridgewaterApi->createUser('username', 'login', 'password', 'profile', 'example.com', 'Example Co');

// Update user profile
$bridgewaterApi->updateUserProfile('login', 'profile2');

```


FeatureServer
---------------------
```php
<?php
use NRC\ProvisioningApi\Api\FeatureServer;

// Simple configuration
$api = new FeatureServer(array(
    'host' => '127.0.0.1',
    'username' => 'login',
    'password' => 'password',
    'role' => 'role'
));

// Explicit configuration of FEATURE and HSS clients
$api = new FeatureServer(
    'clients' => array(
        'feature' => array(
             'host' => '127.0.0.1',
             'username' => 'login',
             'password' => 'password',
             'role' => 'role'
         ),
         'hss' => array(
          'host' => '127.0.0.1',
          'username' => 'login',
          'password' => 'password',
          'role' => 'role'
      )
    )
);
```