<?php

namespace NRC\XmlRemote\tests\unit\api;

use NRC\XmlRemote\Api\StarAcs;

/**
 * @author Tyler Rooney <tyler@tylerrooney.ca>
 * @package XmlRemote
 * @subpackage UnitTests
 */
class StarAcsTest extends \NRC\XmlRemote\tests\unit\UnitTestCase {

	public function testApiDefaultClient() {
		$api = new StarAcs();

		$this->assertInternalType('object', $api->getClient());
	}

}
