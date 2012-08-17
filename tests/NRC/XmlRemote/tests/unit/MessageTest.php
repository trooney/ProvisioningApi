<?php

namespace NRC\XmlRemote\tests\unit;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class MessageTest extends \NRC\XmlRemote\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\XmlRemote\Message
     */
    protected $message;

    protected function setUp()
    {
        $this->message = new \NRC\XmlRemote\Message(array(
	        'data' => array(
		        'foo' => 'bar'
	        )
        ));
    }

    public function testToString()
    {
	    $result = $this->message->to('string');
	    $this->assertInternalType('string', $result);

	    $expected = $this->message->to('string');
	    $result = $this->message->__toString();
	    $this->assertEquals($expected, $result);
    }

	/**
	 * @covers \NRC\XmlRemote\Message::to
	 */
	public function testToArray() {
		$expected = array('foo' => 'bar');
		$result = $this->message->to('array');
		$this->assertEquals($expected, $result);
	}

	/**
	 * @covers \NRC\XmlRemote\Message::to
	 */
	public function testToObject() {
		$expected = new \StdClass();
		$expected->foo = 'bar';

		$result = $this->message->to('object');
		$this->assertEquals($expected, $result);
	}

}
