<?php

namespace NRC\ProvisioningApi;

use ReflectionClass;
use ReflectionProperty;

/**
 * Request
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Request extends Message {

	public $method = null;

	public function __construct(array $config = array()) {
		$defaults = array(
			'method' => null,
		);

		$config += $defaults;

		parent::__construct($config);
	}
}
