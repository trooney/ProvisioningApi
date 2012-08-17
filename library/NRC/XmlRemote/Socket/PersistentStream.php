<?php

namespace NRC\XmlRemote\Socket;

/**
 * Persistent Stream
 *
 * Handle streams where no default EOF character is specified
 *
 * @see XmlRemote\Socket\PersistentStream:readUntil()
 */
class PersistentStream extends \lithium\net\socket\Stream {

	public function __construct(array $config = array()) {
		$defaults = array(
			'timeout' => 15,
		);

		$config += $defaults;

		parent::__construct($config);
	}

	/**
	 * Read the stream until reaching the EOF character(s)
	 *
	 * @param $eof
	 * @return bool|string
	 */
	public function readUntil($eof) {
		if (!is_resource($this->_resource)) {
			return false;
		}

		$time = time();
		$eofOffset = strlen($eof) * -1;
		$buffer = '';

		while (!feof($this->_resource)) {
			$char = fgetc($this->_resource);
			$buffer .= $char;

			if (substr($buffer, $eofOffset) === $eof) {
				break;
			}
			if ((time() - $time) > $this->_config['timeout']) {
				break;
			}
		}

		return $buffer;
	}
}