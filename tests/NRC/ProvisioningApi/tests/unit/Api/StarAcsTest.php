<?php

namespace NRC\ProvisioningApi\tests\unit\api;

use NRC\ProvisioningApi\Api\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

	public function testApiDefaultClient() {
		$api = new StarAcs();

		$this->assertInternalType('object', $api->getClient());
	}

}
