<?php

namespace Src\RouterBoard;

class IPValidator extends AbstractRouterBoard {

	/**
	 * Check if string is valid IPv4 address
	 * @param string $addr
	 * @return boolean
	 */
	public function ipv4validator($addr) {
		$addr = gethostbyname( $addr );
		if (filter_var ( $addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) === false) {
			$this->logger->log ( "IPv4 address is not valid: '" . $addr . "', sorry.", $this->logger->setError() );
			return false;
		}
		return true;
	}

}
