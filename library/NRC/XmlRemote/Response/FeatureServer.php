<?php

namespace NRC\XmlRemote\Response;

use SimpleXMLElement;
use NRC\XmlRemote\Util\Xml;
use NRC\XmlRemote\Exceptions\ResponseException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class FeatureServer extends \NRC\XmlRemote\Response {

	/**
	 * Status of the response
	 *
	 * @var array
	 */
	public $status = array('code' => 'OKAY', 'message' => 'OKAY');

	/**
	 * Checks the success of the request
	 *
	 * @return bool success
	 */
	public function success() {
		return $this->status['code'] == 'OKAY';
	}

	/**
	 * Decode a FeatureServer XML request into a simple array
	 *
	 * @param \SimpleXMLElement $xml
	 * @return array
	 */
	protected function _decode(\SimpleXMLElement $xml) {

		list($status) = $xml->xpath('/Response/@Status');

		if ((string)$status == 'ERROR') {
			list($code) = $xml->xpath('/Response/Error/@Description');
			list($message) = $xml->xpath('/Response/Error/@Message');

			$this->status = array(
				'code' => (string)$code,
				'message' => (string)$message
			);
		}

		unset($xml['Status']);
		unset($xml['RequestId']);

		if ($this->success()) {
			return Xml::simpleXMLToArray($xml);
		}

		return array();
	}


}
