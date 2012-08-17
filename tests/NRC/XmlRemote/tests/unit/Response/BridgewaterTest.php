<?php

namespace NRC\XmlRemote\tests\unit\Response;

use NRC\XmlRemote\Response\Bridgewater;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class BridgewaterTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

	/**
	 * @var \NRC\XmlRemote\Response\Bridgewater
	 */
	protected $response;

	/**
	 * @expectedException \NRC\XmlRemote\Exceptions\ResponseException
	 */
	public function testMalformedXmlResponse() {
		new Bridgewater(array(
			'body' => 'invalid xml string'
		));
	}

	public function testResponseSuccess() {
		$response = $this->getResponse('Bridgewater', 'success');

		$this->assertTrue($response->success());

		$expected = array('code' => 200, 'message' => 'OK');
		$this->assertEquals($expected, $response->status);
	}

	public function testResponseFailure() {
		$response = $this->getResponse('Bridgewater', 'error');

		$this->assertFalse($response->success());

		$expected = array('code' => 'FOO-007', 'message' => 'error');
		$this->assertEquals($expected, $response->status);
	}

	public function testBody() {
		$response = $this->getResponse('Bridgewater', 'success');

		$expected = array(
			'foo' => 'bar'
		);
		$this->assertEquals($expected, $response->to('array'));
	}

}
