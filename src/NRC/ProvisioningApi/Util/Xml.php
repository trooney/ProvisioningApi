<?php

namespace NRC\ProvisioningApi\Util;

/**
 * Xml
 *
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 */
class Xml {

	public static function arrayToSimpleXml(\SimpleXMLElement &$xmlEl, $data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				if (!is_numeric($key)) {
					$subNode = $xmlEl->addChild($key);
					self::arrayToSimpleXml($subNode, $value);
				} else {
					self::arrayToSimpleXml($xmlEl, $value);
				}
			} else {
				$xmlEl->addChild($key, $value);
			}
		}
	}

	public static function simpleXMLToArray(\SimpleXMLElement $xml, $attributesKey = null, $childrenKey = null, $valueKey = null) {

		if ($childrenKey && !is_string($childrenKey)) {
			$childrenKey = '@children';
		}
		if ($attributesKey && !is_string($attributesKey)) {
			$attributesKey = '@attributes';
		}
		if ($valueKey && !is_string($valueKey)) {
			$valueKey = '@values';
		}

		$return = array();
		$name = $xml->getName();
		$_value = trim((string)$xml);
		if (!strlen($_value)) {
			$_value = null;
		}
		;

		if ($_value !== null) {
			if ($valueKey) {
				$return[$valueKey] = $_value;
			} else {
				$return = $_value;
			}
		}

		$children = array();
		$first = true;
		foreach ($xml->children() as $elementName => $child) {
			$value = self::simpleXMLToArray($child, $attributesKey, $childrenKey, $valueKey);
			if (isset($children[$elementName])) {
				if (is_array($children[$elementName])) {
					if ($first) {
						$temp = $children[$elementName];
						unset($children[$elementName]);
						$children[$elementName][] = $temp;
						$first = false;
					}
					$children[$elementName][] = $value;
				} else {
					$children[$elementName] = array($children[$elementName], $value);
				}
			} else {
				$children[$elementName] = $value;
			}
		}
		if ($children) {
			if ($childrenKey) {
				$return[$childrenKey] = $children;
			} else {
				$return = array_merge($return, $children);
			}
		}

		$attributes = array();
		foreach ($xml->attributes() as $name => $value) {
			$attributes[$name] = trim($value);
		}
		if ($attributes) {
			if ($attributesKey) {
				$return[$attributesKey] = $attributes;
			} else {
				$return = array_merge((array) $return, $attributes);
			}
		}

		return $return;
	}

}
