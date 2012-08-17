<?php

namespace NRC\XmlRemote\tests\unit;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class ResponseTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\XmlRemote\Response
	 */
	protected $response;

	protected function setUp() {
		$this->response = new \NRC\XmlRemote\Response();
	}

	/**
	 * @covers \NRC\XmlRemote\Response::_decode
	 */
	public function testDecodeEmptyByDefault() {
		$result = $this->response->to('array');
		$this->assertEmpty($result);
	}

	/**
	 * @covers \NRC\XmlRemote\Response::_decode
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
