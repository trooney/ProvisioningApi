<?php

namespace NRC\XmlRemote\tests\unit;

use \SimpleXMLElement;



/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
abstract class UnitTestCase extends \NRC\XmlRemote\tests\XmlRemoteTestCase
{

	protected $_xPathTarget = null;

	protected function _setXPathTarget(SimpleXMLElement $xml) {
		$this->_xPathTarget = dom_import_simplexml($xml)->ownerDocument;
	}

	protected function _getXPathTarget() {
		return $this->_xPathTarget;
	}

	protected function assertXpathMatch($expected, $xpath, $message = null, \DOMNode $context = null) {
		$dom = $this->_getXPathTarget();
		$xpathObj = new \DOMXPath($dom);

		$context = $context === null
			? $dom->documentElement
			: $context;

		$res = $xpathObj->evaluate($xpath, $context);

		$this->assertEquals(
			$expected,
			$res,
			$message
		);
	}



}
