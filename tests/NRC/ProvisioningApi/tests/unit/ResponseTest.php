<?php

namespace NRC\ProvisioningApi\tests\unit;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class ResponseTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\ProvisioningApi\Response
	 */
	protected $response;

	protected function setUp() {
		$this->response = new \NRC\ProvisioningApi\Response();
	}

	/**
	 * @covers \NRC\ProvisioningApi\Response::_decode
	 */
	public function testDecodeEmptyByDefault() {
		$result = $this->response->to('array');
		$this->assertEmpty($result);
	}

	/**
	 * @covers \NRC\ProvisioningApi\Response::_decode
	 */
	public function testToString() {
		$result = $this->response->to('string');

		$this->assertInternalType('string', $result);
	}

	public function testGetStatus() {
		$expected = '200 OK';
		$this->assertEquals($expected, $this->response->getStatus());
	}

	public function testGetSuccess() {
		$this->assertTrue($this->response->success());
	}
}
