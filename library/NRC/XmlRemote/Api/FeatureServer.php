<?php

namespace NRC\XmlRemote\Api;

use NRC\XmlRemote\Exceptions\ClientException;

/**
 * FeatureServer
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class FeatureServer extends \NRC\XmlRemote\Api {

	public function login($clientName, $password, $role) {
		if ($this->requestId) {
			return null;
		}

		$params = array(
			'Authentication' => array(
				'ApiName' => $clientName,
				'Password' => $password,
				'Role' => $role,
			)
		);

		// MANDATORY
		$this->requestId = rand(1000, 9999);

		$response = $this->request('LOGIN', $params);

		if (!$response->success()) {
			throw new ClientException('FeatureServer: Authentication failed.');
		}

		return $response;
	}

	public function logoff($clientName) {
		$params = array(
			'Authentication' => array(
				'ApiName' => $clientName,
			)
		);

		return $this->request('LOGOFF', $params);
	}

	public function getServiceListByUserId($pubUserId) {
		$params = array(
			'ServiceList' => array(
				'PubUserId' => $pubUserId,
			)
		);

		return $this->request('READ', $params);
	}

	public function getRegistrationDataByUserId($pubUserId) {
		$params = array(
			'RegistrationData' => array(
				'PubUserId' => $pubUserId,
			)
		);

		return $this->request('READ', $params);
	}

	public function getAllPartyDetails() {
		$params = array(
			'PartyId' => null,
		);

		return $this->request('READALL', $params);
	}

}
