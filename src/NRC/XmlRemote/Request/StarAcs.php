<?php

namespace NRC\XmlRemote\Request;

use SimpleXMLElement;
use NRC\XmlRemote\Util\Xml;

class StarAcs extends \NRC\XmlRemote\Request {

	/**
	 * Data is already encoded StdClass, no need to encode
	 *
	 * @param array $options
	 * @return \SimpleXMLElement
	 */
	public function _encode(array $options = array()) {
		return $options['data'];
	}

	/**
	 * StarAcs uses SoapClient for transport. Therefore we can't
	 * derive the XML request from the request data. We'll fake it.
	 *
	 * @param string $format
	 * @param array $options
	 * @return mixed
	 */
	public function to($format, array $options = array()) {
		switch ($format) {
			case 'string':
				$xml = new SimpleXMLElement('<SoapEnvelopeFromRequestDoNotTrustThis />');
				Xml::arrayToSimpleXml($xml, $this->data);
				$dom = dom_import_simplexml($xml)->ownerDocument;
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				return $dom->saveXML();
			case 'xml':
			case 'object':
			case 'array':
			default:
				return parent::to($format, $options);
		}
	}

}
