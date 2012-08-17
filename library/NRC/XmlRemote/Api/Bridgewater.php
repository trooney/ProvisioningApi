<?php

namespace NRC\XmlRemote\Api;

use NRC\XmlRemote\Exceptions\ApiException;

/**
 * Bridgewater
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Bridgewater extends \NRC\XmlRemote\Api {

	/**
	 * Default domain
	 *
	 * @var string
	 */
	public $domain = null;

	/**
	 * Default organization
	 *
	 * @var string
	 */
	public $organization = null;

	protected $_classes = array(
		'client' => 'NRC\XmlRemote\Client\Bridgewater',
	);

	public function __construct(array $config = array()) {
		$defaults = array(
			'domain' => null,
			'organization' => null,
		);

		$config += $defaults;

		parent::__construct($config);
	}

	/**
	 * @param string $username
	 * @param string $domain
	 * @return \StdClass
	 * @throws \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function getUser($username, $domain = null) {
		$params = array(
			'user' => array(
				'name' => $username,
				'domain' => array(
					'name' => ($domain ?: $this->domain)
				),
			)
		);

		$response = $this->_client()->request('UserAPI.getUser', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return $response->to('object')->user;
	}

	/**
	 * @param string $username
	 * @param string $domain
	 * @return bool
	 */
	public function userExists($username, $domain = null) {
		try {
			$this->getUser($username, $domain);
		} catch (ApiException $e) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $username
	 * @param string $login
	 * @param string $password
	 * @param string $profile
	 * @param string $domain
	 * @param string $organization
	 * @return int
	 * @throws \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function createUser($username, $login, $password, $profile, $domain = null, $organization = null) {
		$params = array(
			'user' => array(
				'name' => $username,
				'login-name' => $login,
				'password' => array(
					'value' => $password
				),
				'profile-set' => array(
					'qualified-name' => '/' . ($organization ?: $this->organization) . '/' . $profile,
				),
				'organization' => array(
					'qualified-name' => '/' . ($organization ?: $this->organization),
				),
				'domain' => array(
					'name' => $domain ?: $this->domain
				),
			)
		);

		$response = $this->_client()->request('UserAPI.createUser', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return (int) $response->to('object')->user->id;
	}

	/**
	 * @param string $username
	 * @param string $login
	 * @param string $domain
	 * @return bool
	 * @throws \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function deleteUser($username, $login, $domain = null) {
		$params = array(
			'user' => array(
				'name' => $username,
				'login-name' => $login,
				'domain' => array(
					'name' => ($domain ?: $this->domain)
				),
			)
		);

		$response = $this->_client()->request('UserAPI.deleteUser', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return true;
	}

	/**
	 * @param string $organization
	 * @return \StdClass
	 * @throws \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function getOrganization($organization) {
		$params = array(
			'organization' => array(
				'qualified-name' => '/' . $organization,
			),
			'profile-set' => '',
		);

		$response = $this->_client()->request('OrganizationAPI.getOrganization', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return $response->to('object')->organization;
	}

	/**
	 * @param string $organization
	 * @return bool
	 */
	public function organizationExists($organization) {
		try {
			$this->getOrganization($organization);
		} catch (ApiException $e) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $profile
	 * @param string $organization
	 * @return mixed
	 * @throws \NRC\XmlRemote\Exceptions\ApiException
	 */
	public function getUserProfileSet($profile, $organization = null) {
		$params = array(
			'user-profile-set' => array(
				'qualified-name' => '/' . ($organization ? : $this->organization) . '/' . $profile,
				'profile' => null
			),
		);

		$response = $this->_client()->request('UserAPI.getUserProfileSet', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return $response->to('object')->{'user-profile-set'};
	}

	public function updateUserProfile($login, $profile, $organization = null) {
		$params = array(
			'user' => array(
				'login-name' => $login,
				'profile-set' => array(
					'qualified-name' => '/' . ($organization ? : $this->organization) . '/' . $profile,
				),
			)
		);

		$response = $this->_client()->request('UserAPI.updateUser', $params);

		if (!$response->success()) {
			throw new ApiException($response->getStatus());
		}

		return true;
	}

}
