<?php

namespace NRC\ProvisioningApi\tests\unit\Request;

use NRC\ProvisioningApi\Request\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\ProvisioningApi\Request\StarAcs
     */
    protected $request;

    protected function setUp()
    {
        $this->request = new StarAcs(array(
	        'method' => 'wakka',
	        'requestId' => '123',
	        'data' => array(
		        'foo' => 'bar'
	        )
        ));
    }

	/**
	 * @coverage \NRC\ProvisioningApi\Request\StarAcs:to
	 */
	public function testToArray()
	{
		$expected = array('foo' => 'bar');
		$this->assertEquals($expected, $this->request->to('array'));
	}

}
