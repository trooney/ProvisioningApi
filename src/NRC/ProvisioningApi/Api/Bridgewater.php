<?php

namespace NRC\ProvisioningApi\Api;

use NRC\ProvisioningApi\Exceptions\ApiException;

/**
 * Bridgewater
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Bridgewater extends \NRC\ProvisioningApi\Api {

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
		'client' => 'NRC\ProvisioningApi\Client\Bridgewater',
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
	 * @return mixed
	 * @throws \NRC\ProvisioningApi\Exceptions\ApiException
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

        return $this->_request($this->_client(), 'UserAPI.getUser', $params);
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
	 * @return mixed
	 * @throws \NRC\ProvisioningApi\Exceptions\ApiException
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

        return $this->_request($this->_client(), 'UserAPI.createUser', $params);
	}

	/**
	 * @param string $username
	 * @param string $domain
	 * @return mixed
	 * @throws \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function deleteUser($username, $domain = null) {
		$params = array(
			'user' => array(
				'name' => $username,
				'domain' => array(
					'name' => ($domain ?: $this->domain)
				),
			)
		);

        return $this->_request($this->_client(), 'UserAPI.deleteUser', $params);
	}

	/**
	 * @param string $organization
	 * @return mixed
	 * @throws \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function getOrganization($organization) {
		$params = array(
			'organization' => array(
				'qualified-name' => '/' . $organization,
			),
			'profile-set' => '',
		);

        return $this->_request($this->_client(), 'OrganizationAPI.getOrganization', $params);
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
     * @param string $username
     * @param string $profile
     * @param string $domain
     * @param string $organization
     * @return mixed
     * @throws \NRC\ProvisioningApi\Exceptions\ApiException
     */
    public function updateUserProfile($username, $profile, $domain = null, $organization = null)
    {
        $params = array(
            'user' => array(
                'name' => $username,
                'domain' => array(
                    'name' => ($domain ? : $this->domain)
                ),
                'profile-set' => array(
                    'qualified-name' => '/' . ($organization ? : $this->organization) . '/' . $profile,
                ),
            )
        );

        return $this->_request($this->_client(), 'UserAPI.updateUser', $params);
    }

}
