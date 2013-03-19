<?php

namespace NRC\ProvisioningApi\tests\integration\Api;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @category    ProvisioningApi
 * @package     Client
 * @subpackage  IntegrationTests
 */
class StarAcsTest extends \NRC\ProvisioningApi\tests\integration\IntegrationTestCase {

	/**
	 * @var \NRC\ProvisioningApi\Api\StarAcs
	 */
	protected $api = null;

	public function testIsCpeOnlineSuccess() {
		$api = $this->getApi('StarAcs', 'get_cpe_status_success');

		$response = $api->isCpeOnline(123);

		$this->assertInternalType('boolean', $response);
	}

	public function testGetCpeSerialByMacAddressSuccess() {
		$api = $this->getApi('StarAcs', 'find_cpe_by_mac_address');

		$response = $api->getCpeSerialByMacAddress('');

		$this->assertInternalType('int', $response);
	}

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function testGetCpeSerialByMacAddressFailure() {
		$api = $this->getApi('StarAcs', 'error');

		$api->getCpeSerialByMacAddress(123);
	}

	/**
	 * @param $class
	 * @param $type
	 * @return \NRC\ProvisioningApi\Api\StarAcs
	 */
	public function getApi($class, $type) {
		return parent::getApi($class, $type);
	}

}
