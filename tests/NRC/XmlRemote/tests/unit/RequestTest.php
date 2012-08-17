<?php

namespace NRC\XmlRemote\tests\unit;

use NRC\XmlRemote\Request;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class RequestTest extends \NRC\XmlRemote\tests\unit\UnitTestCase
{
    /**
     * @var \NRC\XmlRemote\Request
     */
    protected $request;

    protected function setUp()
    {
        $this->request = new Request();
    }

    public function testTo()
    {
	    $result = $this->request->to('string');
	    $this->assertInternalType('string', $result);

	    $expected = $this->request->to('string');
	    $result = (string) $this->request;
	    $this->assertEquals($expected, $result);

	    $result = $this->request->to('xml');
	    $this->assertInstanceOf('SimpleXMLElement', $result);
    }

}
