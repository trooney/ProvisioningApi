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
class StarAcs extends \NRC\XmlRemote\Response {

	/**
	 * Status of the response
	 *
	 * @var array
	 */
	public $status = array('code' => 100, 'message' => 'success');

	/**
	 * Checks the success of the request
	 *
	 * @return bool success
	 */
	public function success() {
		return $this->status['code'] == 100;
	}

	protected function _decode(\SimpleXMLElement $xml) {

		if ((string)$xml->response_code == 200) {

			$code = (string) $xml->error_code;
			$message = (string)$xml->message;

			if (!$code) {

				$code = $xml->response_code;
				$message = 'Unknown error';

				$xml->registerXPathNamespace('f', "http://www.friendly-tech.com");
				if ($xml->xpath('//f:errormessage')) {
					$message = '';
					foreach ($xml->xpath('//f:errormessage') as $xmlEl) {
						$message .= (string) $xmlEl . ' ';
					}

				}
			}

			$this->status = array(
				'code' => (string)$code,
				'message' => (string)$message
			);
		}

		unset($xml->response_code);

		if ($this->success()) {
			return Xml::simpleXMLToArray($xml);
		}

		return array();
	}


}
