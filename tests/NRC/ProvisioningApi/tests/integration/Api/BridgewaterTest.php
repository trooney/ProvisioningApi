<?php

namespace NRC\ProvisioningApi\tests\integration\Api;

use NRC\ProvisioningApi\Api\Bridgewater;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @category    ProvisioningApi
 * @package     Client
 * @subpackage  IntegrationTests
 */
class BridgewaterTest extends \NRC\ProvisioningApi\tests\integration\IntegrationTestCase {

	protected $username = null;

	protected $login = null;

	protected $password = null;

	protected $profile = null;

	protected $organization = null;

	protected $domain = null;

	protected $rand = null;

	protected function setUp() {
		$this->username =       'username';
		$this->login =          'login';
		$this->password =       'password';
		$this->profile =        'profile';
		$this->organization =   'organization';
		$this->domain =         'domain';
	}

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function testGetUserIsEmpty() {
		$api = $this->getApi('Bridgewater', 'error');

		$api->getUser(null);
	}

	public function testCreateUserSuccess() {
		$api = $this->getApi('Bridgewater', 'create_user_success');

		$result = $api
			->createUser(
				$this->username,
				$this->login,
				$this->password,
				$this->profile,
				$this->domain,
				$this->organization
		);

		$this->assertInternalType('object', $result);
        $this->assertObjectHasAttribute('user', $result);
	}

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function testCreateUserFailure() {
		$api = $this->getApi('Bridgewater', 'error');

		$result = $api->createUser('','','','');
	}

	/**
	 * @depends testCreateUserSuccess
	 */
	public function testGetUser() {
		$api = $this->getApi('Bridgewater', 'get_user_success');

		$result = $api->getUser($this->username);

		$this->assertInternalType('object', $result);
		$this->assertObjectHasAttribute('user', $result);
	}

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function testGetUserFailure() {
		$api = $this->getApi('Bridgewater', 'error');

		$api->getUser('');
	}

	/**
	 * @depends testGetUser
	 */
	public function testUserExists() {
        $api = $this->getApi('Bridgewater', 'get_user_success');
		$result = $api->userExists($this->username);

		$this->assertTrue($result);
	}

	/**
	 * @depends testGetUser
	 */
	public function testUserExistsFailure() {
		$api = $this->getApi('Bridgewater', 'error');

		$result = $api->userExists(md5(time()));

		$this->assertFalse($result);
	}

	/**
	 * @depends testCreateUserSuccess
	 */
	public function testDeleteUser() {
        $api = $this->getApi('Bridgewater', 'delete_user_success');
		$api->deleteUser($this->username, $this->login, $this->domain);
	}

	public function testGetOrganizationSuccess() {
		$api = $this->getApi('Bridgewater', 'get_organization_success');

		$result = $api->getOrganization($this->organization);

		$this->assertInternalType('object', $result);
		$this->assertObjectHasAttribute('organization', $result);
	}

	/**
	 * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
	 */
	public function testGetOrganizationFailure() {
		$api = $this->getApi('Bridgewater', 'error');

		$api->getOrganization('');
	}

	public function testOrganizationExists() {
        $api = $this->getApi('Bridgewater', 'get_organization_success');

		$result = $api->organizationExists($this->organization);

		$this->assertTrue($result);
	}

	public function testOrganizationExistsFailure() {
		$api = $this->getApi('Bridgewater', 'error');

		$result = $api->organizationExists('');

		$this->assertFalse($result);
	}

	/**
	 * @param $class
	 * @param $type
	 * @return \NRC\ProvisioningApi\Api\Bridgewater
	 */
	public function getApi($class, $type) {
		return parent::getApi($class, $type);
	}
}
