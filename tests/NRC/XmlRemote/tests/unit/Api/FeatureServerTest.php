<?php

namespace NRC\XmlRemote\tests\unit\api;

use NRC\XmlRemote\Api\FeatureServer;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class FeatureServerTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

    /**
     * @expectedException \NRC\XmlRemote\Exceptions\ApiException
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
