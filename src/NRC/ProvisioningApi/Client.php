<?php

namespace NRC\ProvisioningApi;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
abstract class Client extends \lithium\core\Object {

	public $scheme = null;

	public $host = null;

	public $port = null;

	public $timeout = null;

	public $persistent = null;

	public $username = null;

	public $password = null;

	public $path = null;

	public $body = array();

	/**
	 * Client connection
	 *
	 * @var mixed
	 */
	protected $_connection = null;

	/**
	 * Holds the message body of the last request
	 *
	 * @var null
	 */
	protected $_lastRequest = null;

	/**
	 * Holds the message body of the last response
	 *
	 * @var string
	 */
	protected $_lastResponse = null;

	/**
	 * Fully-name-spaced class references to client dependencies
	 *
	 * @var array
	 */
	protected $_classes = array();

	public function __construct(array $config = array()) {
		$defaults = array(
			'scheme' => 'http',
			'host' => 'localhost',
			'port' => 80,
			'timeout' => 5,
			'persistent' => false,
			'username' => null,
			'password' => null,
			'path' => null,
			'connection' => null,
		);

		$config += $defaults;

		if ($config['connection']) {
			$this->_connection = $config['connection'];
			unset($config['connection']);
		}


		foreach (array_filter($config) as $key => $value) {
			$this->{$key} = $value;
		}

		parent::__construct($config);
	}

	/**
	 * Raw message body of the last request
	 *
	 * @return string
	 */
	public function getLastRequest() {
		return $this->_lastRequest;
	}

	/**
	 * Raw message body of the last response
	 *
	 * @return string
	 */
	public function getLastResponse() {
		return $this->_lastResponse;
	}

	/**
	 * Connect to remove service
	 *
	 * @abstract
	 * @param array $options
	 * @return bool
	 */
	abstract public function connect(array $options = array());

	/**
	 * @abstract
	 * @param string $method
	 * @param array $data
	 * @return \NRC\ProvisioningApi\Response
	 */
	abstract public function request($method, array $data = array());

	/**
	 * @abstract
	 * @param $method
	 * @param array $data
	 * @return \NRC\ProvisioningApi\Request
	 */
	abstract public function _request($method, array $data = array());

	/**
	 * Instantiates a response object
	 *
	 * @param $response
	 * @return \NRC\ProvisioningApi\Response
	 */
	public function _response($response) {
		return $this->_instance($this->_classes['response'], array(
			'body' => $response
		));
	}

}
