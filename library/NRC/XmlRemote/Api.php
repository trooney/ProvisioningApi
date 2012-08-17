<?php

namespace NRC\XmlRemote;

use NRC\XmlRemote\Exceptions\ApiException;

/**
 * Api
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Api extends \lithium\core\Object {

	protected $_classes = array();

	protected $_clients = array();

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
				}

				unset($config['clients'][$name]);
			}
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
			$config = $options;
			$options['client'] = array('default' => $options);
		}

		foreach ($options['client'] as $name => $_options) {

			$class = null;

			if (isset($this->_classes['client'])) {
				$class = $this->_classes['client'];
			}

			if (isset($classes['clients'][$name])) {
				$class = $classes['clients'][$name];
			}


			if (!$class) {
				throw new ApiException("No client class defined for client {$name}");
			}

			$this->_clients[$name] = $this->_instance($class, $_options);
		}

	}

	/**
	 * @param string $name
	 * @return \NRC\XmlRemote\Client
	 * @throws Exceptions\ApiException
	 */
	protected function _client($name = 'default') {
		if (!isset($this->_clients[$name])) {
			throw new ApiException("Client '{$name}' does not exist");
		}
		return $this->_clients[$name];
	}

	public function getClient($name = 'default') {
		return $this->_client($name);
	}

	public function getLastRequest($name = 'default') {
		return $this->getClient($name)->getLastRequest();
	}

	public function getLastResponse($name = 'default') {
		return $this->getClient($name)->getLastResponse();
	}
}
