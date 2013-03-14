<?php

namespace NRC\XmlRemote\Client;

use NRC\XmlRemote\Exceptions\ClientException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class FeatureServer extends \NRC\XmlRemote\Client {

	/**
	 * @var null
	 */
	public $role = null;

	/**
	 * @var int
	 */
	public $requestId = null;

	/**
	 * Feature server socket connection
	 *
	 * @var \NRC\XmlRemote\Socket\PersistentStream
	 */
	public $_connection;

	/**
	 * Fully-name-spaced class references to FeatureServer dependencies
	 *
	 * @var array
	 */
	protected $_classes = array(
		'connection' => 'NRC\XmlRemote\Socket\PersistentStream',
		'request' => 'NRC\XmlRemote\Request\FeatureServer',
		'response' => 'NRC\XmlRemote\Response\FeatureServer'
	);

	public function __construct(array $config = array()) {
		$defaults = array(
			'role' => null,
			'persistent' => true,
			'scheme' => 'tcp',
			'requestId' => null,
		);

		$config += $defaults;

		parent::__construct($config);
	}

	public function connect(array $options = array()) {

		$defaults = array(
			'scheme' => $this->scheme,
			'host' => $this->host,
			'port' => $this->port,
			'timeout' => $this->timeout,
			'persistent' => $this->persistent,
		);

		if (is_array($this->_connection)) {
			$defaults += $this->_connection;
			$this->_connection = null;
		}

		$options += $defaults;

		if (!$this->_connection) {
			$this->_connection = $this->_instance($this->_classes['connection'], $options);
		}

		return (bool)$this->_connection->open();
	}

    /**
     * @param $method
     * @param array $data
     * @return \NRC\XmlRemote\Request\FeatureServer
     */
    public function _request($method, array $data = array()) {
		$defaults = array(
			'requestId' => $this->requestId
		);

		$options = compact('method', 'data') + $defaults;

		return $this->_instance($this->_classes['request'], $options);
	}

	public function request($method, array $data = array()) {

		$request = $this->_request($method, $data);

		if (!$this->connect()) {
			$uri = sprintf("%s://%s", $this->scheme, $this->host);
			throw new ClientException('Failed to connect to service at ' . $uri);
		}

		// Don't forget the return!
		$this->_connection->write(array('body' => (string)$request . chr(10)));

		$response = $this->_connection->readUntil('</Response>');

		$this->_lastRequest = (string)$request;

		$this->_lastResponse = $response;

		return $this->_response($response);

	}

}
