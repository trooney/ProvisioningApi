<?php

namespace NRC\ProvisioningApi\Client;

use NRC\ProvisioningApi\Exceptions\ClientException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class FeatureServer extends \NRC\ProvisioningApi\Client {

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
	 * @var \NRC\ProvisioningApi\Socket\PersistentStream
	 */
	public $_connection;

	/**
	 * Fully-name-spaced class references to FeatureServer dependencies
	 *
	 * @var array
	 */
	protected $_classes = array(
		'connection' => 'NRC\ProvisioningApi\Socket\PersistentStream',
		'request' => 'NRC\ProvisioningApi\Request\FeatureServer',
		'response' => 'NRC\ProvisioningApi\Response\FeatureServer'
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

            try {
                @$this->_connection->open();
            } catch (\Exception $e) {
                $uri = sprintf(
                    '%s://%s:%s',
                    $this->scheme,
                    $this->host,
                    $this->port
                );
                throw new ClientException("Failed to connect to service at {$uri}");
            }
		}

		return (bool)$this->_connection;
	}

    /**
     * @param $method
     * @param array $data
     * @return \NRC\ProvisioningApi\Request\FeatureServer
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
