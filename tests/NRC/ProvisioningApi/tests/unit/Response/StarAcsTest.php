<?php

namespace NRC\ProvisioningApi\tests\unit\Response;

use NRC\ProvisioningApi\Response\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\ProvisioningApi\Response\StarAcs
	 */
	protected $response;

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ResponseException
	 */
	public function testMalformedSoapResponse() {
		new StarAcs(array(
			'body' => 'invalid xml string'
		));
	}

	public function testResponseSuccess() {
		$response = $this->getResponse('StarAcs', 'success');

		$this->assertTrue($response->success());

		$expected = array('code' => 100, 'message' => 'success');
		$this->assertEquals($expected, $response->status);
	}

	public function testResponseFailure() {
		$response = $this->getResponse('StarAcs', 'error');

		$this->assertFalse($response->success());

		$expected = array('code' => 201, 'message' => 'error');
		$this->assertEquals($expected, $response->status);
	}

	public function testBody() {
		$response = $this->getResponse('StarAcs', 'success');

		$expected = array(
			'Foo' => 'bar'
		);
		$this->assertEquals($expected, $response->to('array'));
	}

	public function testInvalidParametersErrorMessages() {
		$response = $this->getResponse('StarAcs', 'get_cpe_parameters_error');

		$expected = 200;
		$this->assertEquals($expected, $response->status['code']);
	}

}
