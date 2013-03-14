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
    public function estNoClientClass()
    {
	    $api = new Api();

	    $this->assertInternalType('object', $api->getClient());
    }

    public function testMultipleClients()
    {
        $api = new Api(array(
            'clients' => array(
                'foo' => array(
                    'login' => '',
                    'password' => '',
                ),
                'bar' => array(
                    'login' => '',
                    'password' => '',
                )
            )
        ));

        $this->assertInternalType('object', $api->getClient('foo'));
        $this->assertInternalType('object', $api->getClient('bar'));
    }

	public function testApiSpecificClient() {
		$expected = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');

		$api = new Api(array(
			'client' => $expected
		));

		$this->assertEquals($expected, $api->getClient());
	}

    public function testApiMultipleSpecificClient()
    {
        $expectedA = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');
        $expectedB = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');

        $api = new Api(array(
            'clients' => array(
                'foo' => $expectedA,
                'bar' => $expectedB
            )
        ));

        $this->assertEquals($expectedA, $api->getClient('foo'));
        $this->assertEquals($expectedB, $api->getClient('bar'));
    }

	public function testApiMethods() {
		$expected = $this->getMockForAbstractClass('\NRC\XmlRemote\Client');

		$api = new Api(array(
			'client' => $expected
		));

		$this->assertInternalType('null', $api->getLastRequest());
		$this->assertInternalType('null', $api->getLastResponse());
	}


}
