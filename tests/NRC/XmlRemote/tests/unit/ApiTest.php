<?php

namespace NRC\XmlRemote\tests\unit;

use NRC\XmlRemote\Api;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class ApiTest extends \NRC\XmlRemote\tests\unit\UnitTestCase
{

	/**
	 * @expectedException \NRC\XmlRemote\Exceptions\ApiException
	 */
    public function testNoClientClass()
    {
	    $api = new Api();

	    $this->assertInternalType('object', $api->getClient());
    }

	public function testApiSpecificClient() {
		$expected = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');

		$api = new \NRC\XmlRemote\Api(array(
			'client' => $expected
		));

		$this->assertEquals($expected, $api->getClient());
	}

	public function testApiMethods() {
		$expected = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');

		$api = new \NRC\XmlRemote\Api(array(
			'client' => $expected
		));

		$this->assertInternalType('null', $api->getLastRequest());
		$this->assertInternalType('null', $api->getLastResponse());
	}


}
