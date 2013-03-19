<?php

namespace NRC\ProvisioningApi\tests\unit\api;

use NRC\ProvisioningApi\Api\FeatureServer;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class FeatureServerTest extends \NRC\ProvisioningApi\tests\unit\UnitTestCase {

    /**
     * @expectedException \NRC\ProvisioningApi\Exceptions\ApiException
     */
    public function estApiDefaultClientIsInvalid()
    {
        new FeatureServer();
    }

    public function testApiAutoConfig()
    {
        $api = new FeatureServer(array(
            'client' => array(
                'login' => 'default',
                'password' => 'default',
            )
        ));

        $this->assertInternalType('object', $api->getClient('feature'));

        $this->assertInternalType('object', $api->getClient('hss'));
    }

    public function testApiExplicitConfig()
    {
        $api = new FeatureServer(array(
            'clients' => array(
                'feature' => array(
                    'login' => 'default1',
                    'password' => 'default1',
                ),
                'hss' => array(
                    'login' => 'default2',
                    'password' => 'default2',
                )
            )
        ));

        $this->assertInternalType('object', $api->getClient('feature'));

        $this->assertInternalType('object', $api->getClient('hss'));
    }

}
