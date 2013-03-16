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
class Bridgewater extends \NRC\XmlRemote\Response {

	/**
	 * Status of the response
	 *
	 * @var array
	 */
	public $status = array('code' => 200, 'message' => 'OK');

	/**
	 * Checks the success of the request
	 *
	 * @return bool success
	 */
	public function success() {
		return $this->status['code'] == 200;
	}

	protected function _decode(\SimpleXMLElement $xml) {

		if ((bool) $xml->xpath('/response/target/error')) {
			list($code) = $xml->xpath('/response/target/error/code');
			list($message) = $xml->xpath('/response/target/error/message');

			$this->status = array(
				'code' => (string)$code,
				'message' => (string)$message
			);

		}



		if ($this->success()) {
			return Xml::simpleXMLToArray($xml->target->result);
		}

		return array();
	}


}
