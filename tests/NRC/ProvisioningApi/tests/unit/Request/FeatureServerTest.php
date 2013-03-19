<?php

namespace NRC\ProvisioningApi\tests\unit\Request;

use NRC\ProvisioningApi\Request\FeatureServer;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class FeatureServerTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\ProvisioningApi\Request\FeatureServer
     */
    protected $request;

    protected function setUp()
    {
        $this->request = new FeatureServer(array(
	        'method' => 'wakka',
	        'requestId' => '123',
	        'data' => array(
		        'foo' => 'bar'
	        )
        ));
    }

	public function testToXmValues()
	{
		$mapXPathToValue = array(
			'string(/Request/@Action)' => $this->request->method,
			'string(/Request/@RequestId)' => $this->request->requestId,
			'count(/Request/foo)' => 1,
			'string(/Request/foo)' => 'bar',
		);

		$this->_setXPathTarget($this->request->to('xml'));

		foreach ($mapXPathToValue as $query => $val) {
			$this->assertXpathMatch($val, $query, ' value at ' . $query . ' does not match');
		}

	}

}
