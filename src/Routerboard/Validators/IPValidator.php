<?php

namespace Src\RouterBoard;

class IPValidator extends AbstractRouterBoard {

	
	public function ipv4validator($ip) {
		if (filter_var ( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) === false) {
			$this->logger->log ( "IPv4 address is not valid: '" . $ip . "', sorry.", $this->logger->setError() );
			return false;
		}
		return true;
	}

}
