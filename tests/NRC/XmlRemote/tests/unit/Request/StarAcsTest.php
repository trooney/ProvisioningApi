<?php

namespace NRC\XmlRemote\tests\unit\Request;

use NRC\XmlRemote\Request\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\XmlRemote\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\XmlRemote\Request\StarAcs
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
	 * @coverage \NRC\XmlRemote\Request\StarAcs:to
	 */
	public function testToArray()
	{
		$expected = array('foo' => 'bar');
		$this->assertEquals($expected, $this->request->to('array'));
	}

}
