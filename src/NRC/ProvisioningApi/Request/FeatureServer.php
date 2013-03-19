<?php

namespace NRC\ProvisioningApi\Request;

/**
 * FeatureServer
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @category    ProvisioningApi
 * @package     Request
 * @subpackage  UnitTests
 */
class FeatureServer extends \NRC\ProvisioningApi\Request {

	public $requestId = null;

	public function __construct(array $config = array()) {
		$defaults = array(
			'requestId' => null,
		);

		$config += $defaults;

		parent::__construct($config);
	}

	public function _encode(array $options = array()) {
		$defaults = array(
			'data' => $this->data,
			'method' => $this->method,
			'requestId' => $this->requestId,
		);

		$options += $defaults;

		$xml = new \SimpleXMLElement('<Request />');
		$xml->addAttribute('Action', $options['method']);
		$xml->addAttribute('RequestId', $options['requestId']);

		\NRC\ProvisioningApi\Util\Xml::arrayToSimpleXml($xml, $options['data']);

		return $xml;
	}

	public function __toString() {
		return $this->to('string');
	}
}
