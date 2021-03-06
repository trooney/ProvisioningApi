<?php

namespace NRC\ProvisioningApi\tests\unit\Response;

use NRC\ProvisioningApi\Response\FeatureServer;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class FeatureServerTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\ProvisioningApi\Response\FeatureServer
	 */
	protected $response;

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ResponseException
	 */
	public function testMalformedXmlResponse() {
		new FeatureServer(array(
			'body' => 'invalid xml string'
		));
	}

	public function testResponseSuccess() {
		$response = $this->getResponse('FeatureServer', 'success');

		$this->assertTrue($response->success());

		$expected = array('code' => 'OKAY', 'message' => 'OKAY');
		$this->assertEquals($expected, $response->status);
	}

	public function testResponseFailure() {
		$response = $this->getResponse('FeatureServer', 'error');

		$this->assertFalse($response->success());

		$expected = array('code' => 'ERRORTYPE', 'message' => 'error');
		$this->assertEquals($expected, $response->status);
	}

	public function testBody() {
		$response = $this->getResponse('FeatureServer', 'success');

		$expected = array(
			'Foo' => 'bar'
		);
		$this->assertEquals($expected, $response->to('array'));
	}

}
