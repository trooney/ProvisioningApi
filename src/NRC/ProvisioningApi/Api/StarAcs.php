<?php

namespace NRC\ProvisioningApi\Api;

use NRC\ProvisioningApi\Exceptions\ApiException;

/**
 * StarAcs
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class StarAcs extends \NRC\ProvisioningApi\Api {

	const globalParam = 'InternetGatewayDevice';
	const SOURCE_DB = 1;
	const SOURCE_CPE = 0;

	protected $_classes = array(
		'client' => 'NRC\ProvisioningApi\Client\StarAcs',
	);

    protected $_returnType = 'object';

	public function isCpeOnline($deviceSn) {
		try {
			$this->getCpeParameterBySerial($deviceSn, self::globalParam, self::SOURCE_CPE);
		} catch (ApiException $e) {
			return false;
		}
		return true;
	}

	public function getCpeSerialByMacAddress($macAddress) {

		$macAddress = trim(strtoupper($macAddress));

		$params = array(
			'value' => $macAddress
		);

        $response = $this->_request($this->_client(), 'FTFindDeviceByValue', $params);

		return (int) $response->devices->device->serial;
	}

	public function getCpeParameterBySerial($serial, $parameter, $source = self::SOURCE_CPE) {
		$params = array(
			$parameter
		);

		$result = $this->getCpeParametersBySerial($serial, $params, $source);

		return isset($result->{$parameter}) ? $result->{$parameter} : null;
	}

	public function getCpeParametersBySerial($serial, array $parameters = array(), $source = self::SOURCE_CPE) {

		$params = array(
			'devicesn' => $serial,
			'source' => $source,
			'arraynames' => (array) $parameters
		);

        $response = $this->_request($this->_client(), 'FTGetDeviceParameters', $params);

		$results = $response->parameters->parameter;

		if (count($parameters) == 1) {
			$tmp = array($results);

			$results = (object) $tmp;
		}

		$data = array();
		foreach ($results as $field) {
			$key = $field->name;
			$val = isset($field->value) ? $field->value : null;
			$data[$key] = $val;
		}

		return (object) $data;

	}



}
