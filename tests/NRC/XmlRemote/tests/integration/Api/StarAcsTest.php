<?php

namespace NRC\XmlRemote\tests\integration\Api;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @category    XmlRemote
 * @package     Client
 * @subpackage  IntegrationTests
 */
class StarAcsTest extends \NRC\XmlRemote\tests\integration\IntegrationTestCase {

	/**
	 * @var \NRC\XmlRemote\Api\StarAcs
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
	 * @expectedException \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function testGetCpeSerialByMacAddressFailure() {
		$api = $this->getApi('StarAcs', 'error');

		$api->getCpeSerialByMacAddress(123);
	}

	/**
	 * @param $class
	 * @param $type
	 * @return \NRC\XmlRemote\Api\StarAcs
	 */
	public function getApi($class, $type) {
		return parent::getApi($class, $type);
	}

}
