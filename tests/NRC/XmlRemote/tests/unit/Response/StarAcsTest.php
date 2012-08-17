<?php

namespace NRC\XmlRemote\tests\unit\Response;

use NRC\XmlRemote\Response\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\XmlRemote\Response\StarAcs
	 */
	protected $response;

	/**
	 * @expectedException \NRC\XmlRemote\Exceptions\ResponseException
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

}
