<?php

namespace NRC\XmlRemote\Client;

use NRC\XmlRemote\Exceptions\ClientException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class Bridgewater extends \NRC\XmlRemote\Client {

	/**
	 * Bridgewater HTTP connection
	 *
	 * @var \lithium\net\http\Service
	 */
	protected $_connection;

	/**
	 * Fully-name-spaced class references to Bridgewater dependencies
	 *
	 * @var array
	 */
	protected $_classes = array(
		'connection' => 'lithium\net\http\Service',
		'request' => 'NRC\XmlRemote\Request\Bridgewater',
		'response' => 'NRC\XmlRemote\Response\Bridgewater'
	);

	public function __construct(array $config = array()) {
		$defaults = array(
			'path' => '/'
		);

		$config += $defaults;

		parent::__construct($config);
	}

	public function connect(array $options = array()) {

		$defaults = array(
			'socket' => 'lithium\net\socket\Context',
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

		return (bool) $this->_connection->connection();
	}

	/**
	 * @param $method
	 * @param array $data
	 * @return \NRC\XmlRemote\Request\Bridgewater
	 */
	public function _request($method, array $data = array()) {
		$defaults = array(
			'version' => '1.2',
			'principal' => $this->username,
			'credentials' => $this->password,
		);

		$options = compact('method', 'data') + $defaults;

		return $this->_instance($this->_classes['request'], $options);
	}

	/**
	 * @param $method
	 * @param array $data
	 * @return \NRC\XmlRemote\Response\Bridgewater
	 * @throws \NRC\XmlRemote\Exceptions\ClientException
	 */
	public function request($method, array $data = array()) {

		$request = $this->_request($method, $data);

		if (!$this->connect()) {
			$uri = sprintf("%s://%s", $this->scheme, $this->host);
			throw new ClientException('Failed to connect to service at ' . $uri);
		}

		$response = $this->_connection->send('POST', $this->path, array(
			'xml_data' => (string)$request
		));

		$this->_lastRequest = (string)$request;

		$this->_lastResponse = $response;

		return $this->_response($response);

	}

}
