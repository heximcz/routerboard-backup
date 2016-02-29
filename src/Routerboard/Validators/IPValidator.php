<?php

namespace Src\RouterBoard;

class IPValidator extends AbstractRouterBoard {

	/**
	 * Check if string is valid IPv4 address
	 * @param string $addr
	 * @return boolean
	 */
	public function ipv4validator($addr) {
		if ( $this->ifDomainName($addr))
			$addr = gethostbyname( $addr );
		if ( filter_var ( $addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) === false ) {
			$this->logger->log ( "IPv4 address (domain) '" . $addr . "' is not valid or not exist!", $this->logger->setError() );
			return false;
		}
		return true;
	}
	
	/**
	 * Check if a string represents domain name
	 * @param string $domain
	 * @return boolean
	 */
	public function ifDomainName($domain) {
		$validHostnameRegex = "/^[a-zA-Z0-9.\-]{2,256}\.[a-z]{2,6}$/";
		if ( preg_match($validHostnameRegex, $domain) ) {
			return true;
		}
		return false;
	}

}
