<?php

namespace NRC\ProvisioningApi;

use NRC\ProvisioningApi\Client;
use NRC\ProvisioningApi\Exceptions\ApiException;

/**
 * Api
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Api extends \lithium\core\Object {

    const RETURN_RESPONSE = 'response';
    const RETURN_OBJECT = 'object';
    const RETURN_ARRAY = 'array';
    const RETURN_XML = 'xml';

	protected $_classes = array(
        'client' => '\stdClass'
    );

    /**
     * Hold instantiated clients
     *
     * @var array
     */
    protected $_clients = array();

    /**
     * Holds the current return type
     *
     * @var string
     */
    protected $_returnType;

	public function __construct(array $config = array()) {
		$defaults = array(
			'clients' => array()
		);

		if (isset($config['client'])) {
			$config['clients'] = array(
				'default' => $config['client']
			);

			unset($config['client']);
		}

		if (isset($config['clients'])) {
			foreach ($config['clients'] as $name => $client) {
				if (is_object($client)) {
					$this->_clients[$name] = $client;
                    unset($config['clients'][$name]);
                }
			}
		}

        if(isset($config['returnType'])) {
            $this->setReturnType($config['returnType']);

            unset($config['returnType']);
        }

        if (!$this->_returnType) {
            $this->_returnType = self::RETURN_OBJECT;
        }

		foreach (array_filter($config) as $key => $value) {
			$this->{$key} = $value;
		}

		parent::__construct($config);
	}

	public function _init() {
		parent::_init();

		$this->_connect($this->_config);
	}

	protected function _connect(array $options = array()) {
		if ($this->_clients) {
			return;
		}

		if (!isset($options['clients'])) {
			$options['clients'] = array('default' => $options);
		}

		foreach ($options['clients'] as $name => $_options) {

			$class = null;

			if (isset($this->_classes['client'])) {
				$class = $this->_classes['client'];
			}

			if (isset($classes['clients'][$name])) {
				$class = $classes['clients'][$name];
			}

			if (!$class) {
				throw new ApiException("No class defined for client {$name}");
			}

			$this->_clients[$name] = $this->_instance($class, $_options);
		}

	}

	/**
	 * @param string $name
	 * @return \NRC\ProvisioningApi\Client
	 * @throws Exceptions\ApiException
	 */
	protected function _client($name = 'default') {
		if (!isset($this->_clients[$name])) {
			throw new ApiException("Client '{$name}' does not exist");
		}
		return $this->_clients[$name];
	}

    /**
     * Get client
     *
     * @param string $name
     * @return Client
     */
    public function getClient($name = 'default') {
		return $this->_client($name);
	}

    /**
     * Return the last request from specified client
     *
     * @param string $client Name fo the client
     * @return \NRC\ProvisioningApi\Request
     */
    public function getLastRequest($client = 'default') {
		return $this->getClient($client)->getLastRequest();
	}

    /**
     * Return last response from specified client
     *
     * @param string $client Name of the client
     * @return \NRC\ProvisioningApi\Response
     */
    public function getLastResponse($client = 'default') {
		return $this->getClient($client)->getLastResponse();
	}

    /**
     * @param Client $client
     * @param $method
     * @param $params
     * @return string|array|object|\NRC\ProvisioningApi\Response
     * @throws Exceptions\ApiException
     */
    protected function _request(Client $client, $method, $params) {
        $response = $client->request($method, $params);

        if (!$response->success()) {
            throw new ApiException($response->getStatus());
        }

        return $response->to($this->_returnType);
    }

    /**
     * Set new return type
     *
     * @param $type
     * @see self::_request
     */
    public function setReturnType($type) {
        $types = array(
            self::RETURN_RESPONSE,
            self::RETURN_OBJECT,
            self::RETURN_ARRAY,
            self::RETURN_XML
        );

        if ($key = array_search($type, $types, true)) {
            $this->_returnType = $types[$key];
        }
    }

    /**
     * Get the current return type
     *
     * @return string
     */
    public function getReturnType() {
        return $this->_returnType;
    }
}