<?php

namespace NRC\ProvisioningApi\Client;

use NRC\ProvisioningApi\Exceptions\ClientException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class StarAcs extends \NRC\ProvisioningApi\Client {

	/**
	 * StarACS soap connection
	 *
	 * @var \SoapClient
	 */
	protected $_connection = null;

	/**
	 * Fully-name-spaced class references to StarAcs dependencies
	 *
	 * @var array
	 */
	protected $_classes = array(
		'connection' => 'lithium\net\http\Service',
		'request' => 'NRC\ProvisioningApi\Request\StarAcs',
		'response' => 'NRC\ProvisioningApi\Response\StarAcs'
	);

	/**
	 * Indicates whether xDebug is enabled
	 *
	 * @var bool enabled
	 */
	protected $_xdebugEnabled = false;

	public function __construct(array $config = array()) {
		$defaults = array(
			'path' => '/ftacsws/ftacsws.asmx?wsdl'
		);

		$config += $defaults;

		$this->_xdebugEnabled = function_exists('xdebug_enable');

		parent::__construct($config);
	}

	public function connect(array $options = array()) {

		$defaults = array(
			'scheme' => $this->scheme,
			'host' => $this->host,
			'port' => $this->port,
			'path' => $this->path,
			'login' => $this->username,
			'password' => $this->password,
			'connection_timeout' => $this->timeout || 30,
			'keep_alive' => $this->persistent,
			'cache_wsdl' => WSDL_CACHE_BOTH,
			'exceptions' => 1,
			'trace' => false,
		);

		if (is_array($this->_connection)) {
			$defaults += $this->_connection;
			$this->_connection = null;
		}

		$options += $defaults;

		$uri = $options['scheme'] . '://' . $options['host'];

		if (isset($options['port'])) {
			$uri .= ':' . $options['port'];
		}

		if (isset($options['path'])) {
			$uri .= '/' . $options['path'];
		}

        $defaultTimeout = ini_get('default_socket_timeout');
        $timeout = $options['connection_timeout'];

        // @Note Change php config as SoapClient does not respect 'connection_timeout'
        // @See http://php.net/manual/en/soapclient.soapclient.php
        if ($timeout !== $defaultTimeout) {
            ini_set('default_socket_timeout', $timeout);
        }

        // @Note xdebug causes fatal error in SoapClient
		if ($this->_xdebugEnabled) {
			xdebug_disable();
		}

		if (!$this->_connection) {
			try {
				@$this->_connection = new \SoapClient($uri, $options);
			} catch (\SoapFault $e) {
				throw new ClientException("Failed to connect to service at {$uri}", $e->getCode());
			}
		}

        // Restore xdebug
		if ($this->_xdebugEnabled) {
			xdebug_enable();
		}

        // Restore timeout
        if ($timeout !== $defaultTimeout) {
            ini_set('default_socket_timeout', $defaultTimeout);
        }

		return (bool) $this->_connection;
	}

	public function _request($method, array $data = array()) {
		$options = compact('method', 'data');

		return $this->_instance($this->_classes['request'], $options);
	}

	public function request($method, array $data = array()) {

		$request = $this->_request($method, $data);

		if (!$this->connect()) {
			return null;
		}

		if ($this->_xdebugEnabled) {
			xdebug_disable();
		}

		try {
			$soapResponse = $this->_connection->$method($request->to('array'));
			$response = $this->_decodeSoapResponse($soapResponse);
		} catch (\SoapFault $e) {
			throw new ClientException($e->getMessage(), $e->getCode(), $e);
		}

		if ($this->_xdebugEnabled) {
			xdebug_disable();
		}

		$this->_lastRequest = $this->_connection->__getLastRequest();

		$this->_lastResponse = $this->_connection->__getLastResponse();

		return $this->_response($response);

	}

	protected function _decodeSoapResponse($response) {

		if (!is_object($response)) {
			throw new ClientException('SOAP Response must be an object.');
		}

		reset($response);
		list($resultClass, $resultContainer) = each($response);

		if (!$resultContainer || !isset($resultContainer->any)) {
			throw new ClientException('SOAP Response contained unexpected container');
		}

		return $resultContainer->any;
	}

}
