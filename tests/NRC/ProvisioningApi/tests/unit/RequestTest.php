<?php

namespace NRC\ProvisioningApi\tests\unit;

use NRC\ProvisioningApi\Request;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class RequestTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\ProvisioningApi\Request
     */
    protected $request;

    protected function setUp()
    {
        $this->request = new Request();
    }

    public function testTo()
    {
	    $result = $this->request->to('string');
	    $this->assertInternalType('string', $result);

	    $expected = $this->request->to('string');
	    $result = (string) $this->request;
	    $this->assertEquals($expected, $result);

	    $result = $this->request->to('xml');
	    $this->assertInstanceOf('SimpleXMLElement', $result);
    }

}
