<?php

namespace NRC\XmlRemote\Api;

use NRC\XmlRemote\Exceptions\ClientException;

/**
 * FeatureServer
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class FeatureServer extends \NRC\XmlRemote\Api {

    static $_CLIENT_HSS = 'hss';
    static $_CLIENT_FEATURE = 'feature';

    protected $_classes = array(
        'client' => 'NRC\XmlRemote\Client\FeatureServer',
    );

    /**
     * FeatureServer handles two client connections
     *      feature - Connection to the feature data port
     *      hss - Connection to the hss data port
     *
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $defaults = array (
            'clients' => array(
                'feature' => array('port' => 7856),
                'hss' => array('port' => 7857),
            )
        );

        if (!isset($config['client']) && !isset($config['clients'])) {
            $config['client'] = $config;
        }


        if (isset($config['client'])) {
            foreach ($defaults['clients'] as $_client => $_config) {
                $config['clients'][$_client] = $config['client'];
            }

            unset($config['client']);
        }

        $config = array_replace_recursive($defaults, $config);

        parent::__construct($config);
    }

	public function login($client) {
		if ($this->_client($client)->requestId) {
			return null;
		}

        $config = $this->_client($client)->_config;

		$params = array(
			'Authentication' => array(
				'ClientName' => $config['username'],
				'Password' => $config['password'],
				'Role' => $config['role'],
			)
		);

		// MANDATORY
		$this->_client($client)->requestId = rand(1000, 9999);

		$response = $this->_client($client)->request('LOGIN', $params);

		if (!$response->success()) {
            throw new ClientException('FeatureServer: Client "' . $client . '" failed to authenticate.');
		}


		return $response;
	}

	public function logoff($client) {
        if ($this->_client($client)->requestId) {
            return null;
        }

        $config = $this->_client($client)->_config;

		$params = array(
			'Authentication' => array(
				'ClientName' => $config['username'],
			)
		);

		return $this->_client($client)->request('LOGOFF', $params);
	}

    public function getHssPublicId($puidUser)
    {
        $params = array(
            'PublicId' => array(
                'PuidUser' => $puidUser,
            )
        );

        $response = $this->_client(self::$_CLIENT_HSS)->request('READ', $params);

        print($response->body) . "\n\n";

        return $response;
    }

	public function getHssRegistrationData($puidUser) {
		$params = array(
			'RegistrationData' => array(
				'PuidUser' => $puidUser,
			)
		);

        $response = $this->_client(self::$_CLIENT_HSS)->request('READ', $params);

        print($response->body) . "\n\n";

        return $response;
	}

	public function getFeatureAllServiceList() {
		$params = array(
			'ServiceList' => array(
                'PubUserId' => ''
            )
		);

		$response = $this->_client(self::$_CLIENT_FEATURE)->request('READALL', $params);

        return $response;
	}

    public function getFeatureServiceList($puidUser)
    {
        $params = array(
            'ServiceList' => array(
                'PubUserId' => $puidUser
            )
        );

        $response = $this->_client(self::$_CLIENT_FEATURE)->request('READ', $params);

        print($response->body) . "\n\n";

        return $response;
    }

}
