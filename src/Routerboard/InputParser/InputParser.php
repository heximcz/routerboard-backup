<?php

namespace Src\RouterBoard;

use Src\RouterBoard\IPValidator;

class InputParser extends AbstractRouterBoard {
	
	private $paddr = array();
	private $validate;
	
	public function __construct($config, $logger, $input) {
		parent::__construct( $config, $logger );
		$this->validate = new IPValidator( $config, $logger );
		$this->inputParserArray( $input );
	}

	/**
	 * Parsed ip address and SSH ports
	 * @return mixed (array, false)
	 */
	public function getAddr() {
		if ( !empty( $this->paddr) )
			return $this->paddr;
		return false;
	}
	
	/**
	 * Parse input IP address parameters and check different SSH port
	 * @param array $input
	 */
	private function inputParserArray( $input ) {
		$i = 0;
		foreach ($input as $addr) {
			if ( strpos( $addr, ':' ) === false) {
				$this->paddr[$i]['addr'] = $addr;
				$this->paddr[$i++]['port'] = $this->config['routerboard']['ssh-port'];
				continue;
			}
			$parse = explode(':', $addr,2);
			$this->paddr[$i]['addr'] = $parse[0];
			$this->paddr[$i++]['port'] = $parse[1];
		}
		$this->paddr = array_filter( $this->paddr, array( $this, "validateParsedAddress" ) );
	}

	/**
	 * Check if domain or IP address is valid and port range is between 1-65535
	 * @param string $addr
	 */
	private function validateParsedAddress($addr) {
		if ( $this->validate->ipv4validator( $addr['addr'] ) ) {
			if ( $addr['port'] > 0 && $addr['port'] < 65536 )
				return true;
			$this->logger->log ( "SSH port in: '" . $addr['addr'] . ':' . $addr['port'] . "' is out of range 1-65535 !", $this->logger->setError() );
		}
		return false;
	}

}
