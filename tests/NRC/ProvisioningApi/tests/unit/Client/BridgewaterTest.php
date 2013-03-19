<?php

namespace NRC\ProvisioningApi\tests\unit\Client;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class BridgewaterTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\ProvisioningApi\Client\Bridgewater
     */
    protected $client = null;

    protected function setUp()
    {
	    $this->client = $this->getClient('Bridgewater', 'success');
    }

	public function testGetLastResponse() {

		$result = $this->client->getLastResponse();
		$this->assertEmpty($result);

		$this->client->request('foo.bar', array('baz' => 'qux'));

		$result = $this->client->getLastResponse();
		$this->assertNotEmpty($result);
	}

	public function testGetLastRequest() {
		$result = $this->client->getLastRequest();
		$this->assertEmpty($result);

		$this->client->request('foo.bar', array('baz' => 'qux'));

		$expected = $this->client->_request('foo.bar', array('baz' => 'qux'))->to('string');
		$result = $this->client->getLastRequest();
		$this->assertEquals($expected, $result);
	}

}
