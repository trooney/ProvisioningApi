<?php

namespace NRC\ProvisioningApi\tests\unit\api;

use NRC\ProvisioningApi\Api\Bridgewater;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class BridgewaterTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

	public function testApiDefaultClient() {
		$api = new Bridgewater();

		$this->assertInternalType('object', $api->getClient());
	}

}
