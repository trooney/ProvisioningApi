<?php

namespace NRC\ProvisioningApi\tests;

use \SimpleXMLElement;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package ProvisioningApi
 */
abstract class ProvisioningApiTestCase extends \PHPUnit_Framework_TestCase {

	public function getApi($class, $type) {
		return $this->_instance("\\NRC\ProvisioningApi\\Api\\{$class}", array(
			'client' => $this->getMockClient($class, $type)
		));
	}

	public function getResponse($class, $type) {
		return $this->_instance("\\NRC\\ProvisioningApi\\Response\\{$class}", array(
			'body' => $this->_connectionResponse($class, $type)
		));
	}

	public function getClient($class, $type) {
		return $this->_instance("\\NRC\\ProvisioningApi\\Client\\{$class}", array(
			'connection' => $this->_mockConnection($class, $type),
		));
	}

	public function getMockClient($class, $type) {
		$response = $this->getResponse($class, $type);

		$mock = $this->getMock(
			"\\NRC\\ProvisioningApi\\Client\\{$class}"
		);

		$mock->expects($this->any())
			->method('request')
			->will($this->returnValue($response));

		$mock->expects($this->any())
			->method('getLastRequest')
			->will($this->returnValue(null));

		$mock->expects($this->any())
			->method('getLastResponse')
			->will($this->returnValue($response->to('string')));

		return $mock;
	}

	protected function _mockConnection($class, $type) {
		$response = $this->_xml($class, $type);

		switch ($class) {
			case 'StarAcs':

				$soapObj = $this->_starAcsSoapObject($response);

				$method = 'Foo';
				$mock = $this->getMock('SoapClient', array($method, '__getLastRequest', '__getLastResponse'), array(), '', false);
				$mock->expects($this->any())
					->method($method)
					->will($this->returnValue($soapObj));

				$mock->expects($this->any())
					->method('__getLastRequest')
					->will($this->returnValue($response));

				$mock->expects($this->any())
					->method('__getLastResponse')
					->will($this->returnValue($response));

				return $mock;

			case 'BridgeWater':
			case 'FeatureServer':
			default:
				$mock = $this->getMock('lithium\net\http\Service', array('connect', 'send'));
				$mock->expects($this->any())
					->method('connect')
					->will($this->returnValue(true));
				$mock->expects($this->any())
					->method('send')
					->will($this->returnValue($response));
				return $mock;
		}
	}

	protected function _connectionResponse($class, $type) {
		$xml = $this->_xml($class, $type);

		switch ($class) {
			case 'StarAcs':
				// Easiest just to get the response from a SOAP object
				$soap = $this->_starAcsSoapObject($xml);
				list($result, $obj) = each($soap);
				return $obj->any;
			case 'BridgeWater':
			case 'FeatureServer':
			default:
				return $xml;
		}
	}

	protected function _starAcsSoapObject($xml) {
		$doc = new \DOMDocument();
		$doc->loadXML($xml);

		$path = new \DOMXPath($doc);
		$path->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
		$path->registerNamespace('f', "http://www.friendly-tech.com");

		$el = $path->query("/soap:Envelope/soap:Body/f:*[1]/f:*[1]");
		if (count($el) == 0)
			throw new \Exception('ProvisioningApiTestCase - Malformed Soap Fixture');
		$attr = $el->item(0)->nodeName;

		/** @var $el \DomNodeList */
		$el = $path->query('/soap:Envelope/soap:Body/f:*[1]/f:*[1]/f:result');
		if (count($el) == 0)
			throw new \Exception('ProvisioningApiTestCase - Malformed Soap Fixture');
		$val = $doc->saveXML($el->item(0));

		$valObj = new \StdClass;
		$valObj->any = $val;

		$obj = new \StdClass;
		$obj->{$attr} = $valObj;

		return $obj;
	}

	protected function _xml($class, $type) {
		return file_get_contents(__DIR__ . "/fixture/Response/{$class}/{$type}.xml");
	}

	protected function _instance($class, $args) {
		return new $class($args);
	}
}