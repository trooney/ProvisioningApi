<?php

namespace NRC\XmlRemote\Request;

use NRC\XmlRemote\Exceptions\RequestException;

/**
 * Bridgewater
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Bridgewater extends \NRC\XmlRemote\Request {

	public $version = null;

	public $principal = null;

	public $credentials = null;

	public function __construct(array $config = array()) {
		$defaults = array(
			'version' => '1.2',
			'method' => null,
			'parameter' => array()
		);

		$config += $defaults;

		parent::__construct($config);
	}

	public function _encode(array $options = array()) {
		$defaults = array(
			'method' => $this->method,
			'data' => $this->data,
			'version' => $this->version,
			'principal' => $this->principal,
			'credentials' => $this->credentials,
		);

		$options += $defaults;

		$xml = new \SimpleXMLElement('<request />');
		$xml->addAttribute('version', $options['version']);

		if ($options['principal']) {
			$xml->addAttribute('principal', $options['principal']);
		}

		if ($options['credentials']) {
			$xml->addAttribute('credentials', $options['credentials']);
		}

		if (!preg_match('/(.+)\.(.+)/', $options['method'])) {
			throw new RequestException("Invalid method invocation. Methods must be scoped, i.e., foo.bar");
		}

		list($name, $operation) = explode('.', $options['method']);

		$target = $xml->addChild('target');
		$target->addAttribute('name', $name);
		$target->addAttribute('operation', $operation);

		\NRC\XmlRemote\Util\Xml::arrayToSimpleXml($target, array('parameter' => $options['data']));

		return $xml;
	}

	public function __toString() {
		return $this->to('string');
	}
}
