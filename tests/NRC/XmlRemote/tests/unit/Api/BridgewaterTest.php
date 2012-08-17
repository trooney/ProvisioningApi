<?php

namespace NRC\XmlRemote\tests\unit\api;

use NRC\XmlRemote\Api\Bridgewater;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class BridgewaterTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

	public function testApiDefaultClient() {
		$api = new Bridgewater();

		$this->assertInternalType('object', $api->getClient());
	}

}
