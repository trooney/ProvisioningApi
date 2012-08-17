XML Remote
==========

Unified XML interface for Bridgewater AAA, StarACS and Lucent Feature Server interfaces. Uses Lithium PHP framework.


Example
-------

```php
<?php
require_once __DIR__ . '/../tests/autoload.php';

use NRC\XmlRemote\Api\Bridgewater;
use NRC\XmlRemote\Api\StarAcs;
use NRC\XmlRemote\Exceptions\ApiException;

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