<?php

namespace NRC\ProvisioningApi\tests\unit\Request;

use NRC\ProvisioningApi\Request\Bridgewater;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class BridgewaterTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\ProvisioningApi\Request\Bridgewater
     */
    protected $request;

    protected function setUp()
    {
        $this->request = new Bridgewater(array(
	        'version' => '1',
	        'principal' => 'login',
	        'credentials' => 'password',
	        'method' => 'foo.bar',
	        'data' => array(
		        'foo' => 'bar'
	        )
        ));
    }

	public function testToXmlEnvelopeValues()
	{

		$mapXPathToValue = array(
			'string(/request/@version)' => $this->request->version,
			'string(/request/@principal)' => $this->request->principal,
			'string(/request/@credentials)' => $this->request->credentials,
			'string(/request/target/@name)' => 'foo',
			'string(/request/target/@operation)' => 'bar',
			'count(/request/target/parameter)' => 1,
			'string(/request/target/parameter/foo)' => 'bar',
		);

		$this->_setXPathTarget($this->request->to('xml'));

		foreach ($mapXPathToValue as $query => $val) {
			$this->assertXpathMatch($val, $query, ' value at ' . $query . ' does not match');
		}

	}

}
