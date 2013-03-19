<?php

namespace NRC\ProvisioningApi;

use SimpleXMLElement;

use NRC\ProvisioningApi\Exceptions\ResponseException;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 * @subpackage UnitTests
 */
class Response extends Message
{

    public $data = array();

    public $status = array('code' => 200, 'message' => 'OK');

    public function _init()
    {
        parent::_init();

        try {
            $xml = new SimpleXMLElement($this->body);
        } catch (\Exception $e) {
            $this->status = array(500 => 'Internal Server Error');
            throw new ResponseException("Malformed XML Response");
        }

        $this->data = $this->_decode($xml);
    }

    public function to($format, array $options = array())
    {
        switch ($format) {
            case 'string':
                return $this->body;
            case 'xml':
            case 'object':
            case 'array':
            default:
                return parent::to($format, $options);
        }
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        return $this->status['code'] . ' ' . $this->status['message'];
    }

    /**
     * @return bool
     */
    public function success()
    {
        return true;
    }


}
