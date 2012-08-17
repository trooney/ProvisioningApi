<?php

namespace NRC\XmlRemote\tests\unit\Client;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\XmlRemote\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\XmlRemote\Client\StarAcs
     */
    protected $client = null;

    protected function setUp()
    {
	    $this->client = $this->getClient('StarAcs', 'success');
    }

	public function testGetLastResponse() {

		$result = $this->client->getLastResponse();
		$this->assertEmpty($result);

		$this->client->request('foo', array('baz' => 'qux'));

		$result = $this->client->getLastResponse();
		$this->assertNotEmpty($result);
	}

	public function testGetLastRequest() {
		$result = $this->client->getLastRequest();
		$this->assertEmpty($result);

		$this->client->request('foo', array('baz' => 'qux'));

		$result = $this->client->getLastRequest();
		$this->assertNotEmpty($result);
	}

}
