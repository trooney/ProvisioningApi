<?php

namespace NRC\ProvisioningApi\tests\unit;

use NRC\ProvisioningApi\Api;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class ApiTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
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
		$expected = $this->getMockForAbstractClass('\NRC\ProvisioningApi\Client');

		$api = new Api(array(
			'client' => $expected
		));

		$this->assertEquals($expected, $api->getClient());
	}

    public function testApiMultipleSpecificClient()
    {
        $expectedA = $this->getMockForAbstractClass('\NRC\ProvisioningApi\Client');
        $expectedB = $this->getMockForAbstractClass('\NRC\ProvisioningApi\Client');

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
		$expected = $this->getMockForAbstractClass('\NRC\ProvisioningApi\Client');

		$api = new Api(array(
			'client' => $expected
		));

		$this->assertInternalType('null', $api->getLastRequest());
		$this->assertInternalType('null', $api->getLastResponse());
	}


}
