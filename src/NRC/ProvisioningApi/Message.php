<?php

namespace NRC\ProvisioningApi;

use NRC\ProvisioningApi\Util\Xml;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class Message extends \lithium\core\Object {

	/**
	 * Raw XML String
	 *
	 * @var string
	 */
	public $body = null;

	/**
	 * Data processed from XML String
	 *
	 * @var array
	 */
	public $data = array();

	public function __construct(array $config = array()) {
		$defaults = array(
			'body' => '<xml />',
			'data' => array(),
		);

		$config += $defaults;

		foreach (array_filter($config) as $key => $value) {
			$this->{$key} = $value;
		}

		parent::__construct($config);
	}

	public function _encode(array $options = array()) {
		return $xml = new \SimpleXMLElement('<Message />');
	}

	protected function _decode(\SimpleXMLElement $xml) {
		return array();
	}

	public function to($format, array $options = array()) {
		switch ($format) {
			case 'xml':
				return $this->_encode($options);
			case 'object':
				return json_decode(json_encode($this->data), FALSE);
			case 'array':
				return (array) $this->data;
			case 'string':
			default:
				$dom = dom_import_simplexml($this->to('xml'))->ownerDocument;
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				return $dom->saveXML();
		}
	}

	public function __toString() {
		return $this->to('string');
	}


}
