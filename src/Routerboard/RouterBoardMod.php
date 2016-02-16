<?php

namespace Src\RouterBoard;

use Src\RouterBoard\IPValidator;
use Src\RouterBoard\SSHConnector;

class RouterBoardMod extends AbstractRouterBoard implements IRouterBoardMod {
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::addNewIP()
	 */
	public function addNewIP(array $addr) {
		$validator = new IPValidator($this->config, $this->logger);
		$dbconnect = new $this->config['database']['data-adapter']($this->config, $this->logger);
		$user = new SSHConnector($this->config, $this->logger);

		foreach ($addr as $ipaddr) {
			// is ip addr valid ?
			if ( $validator->ipv4validator($ipaddr) ) {
				if ( !$dbconnect->checkExistIP($ipaddr) ) {
				// create backup user and on success add ip to backup list in db
					if ( $identity = $user->createBackupAccount($ipaddr) ) {
						if ( $dbconnect->addIP($ipaddr, $identity) )
							$this->logger->log( "The router: '" . $identity . "'@'" . $ipaddr . "' has been successfully added to database." );
					}
				}
				else 
					$this->logger->log( "The IP address " . $ipaddr . " already exists in the database!", $this->logger->setError() );
			}
		}
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::deleteIP()
	 */
	public function deleteIP(array $addr) {
		$validator = new IPValidator($this->config, $this->logger);
		$dbconnect = new $this->config['database']['data-adapter']($this->config, $this->logger);
		foreach ($addr as $ipaddr) {
			if ( $validator->ipv4validator($ipaddr) ) {
				if ( $dbconnect->deleteIP($ipaddr) ) 
					$this->logger->log( "The IP '" .$ipaddr . "' has been deleted successfully.");
				else
					$this->logger->log( "The delete of the IP '" .$ipaddr . "' from database fails.", $this->logger->setError() );
			}
				
		}
		
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::updateIP()
	 */
	public function updateIP(array $addr) {
		if ( count($addr) != 2 ) {
			$this->logger->log( "The delete is not possible. Enter only two IP addresses: -i ip -i ip",$this->logger->setError() );
			return;
		}
		$validator = new IPValidator($this->config, $this->logger);
		$dbconnect = new $this->config['database']['data-adapter']($this->config, $this->logger);
		foreach ($addr as $addr) {
			if ( !$validator->ipv4validator($addr) )
				return;
		}
		$ip0 = $dbconnect->checkExistIP($addr[0]);
		$ip1 = $dbconnect->checkExistIP($addr[1]);
		if ( $ip0 && $ip1 ) {
			$this->logger->log("Both IP addresses already exist in database!",$this->logger->setError() );
			return;
		}
		if ( !$ip0 && !$ip1 )
		{
			$this->logger->log("Neither of the two IP address exist in the database!",$this->logger->setError() );
			return;
		}
		
		$ssh = new SSHConnector($this->config, $this->logger);
		if ($ip0) {
			if ( $identity = $ssh->createBackupAccount($addr[1]) ) {
				if ( $dbconnect->updateIP( $addr[0], $addr[1], $identity ))
					$this->logger->log("The update has been successful." );
				else
					$this->logger->log("The update IP '" . $addr[0] . "' database error.", $this->logger->setError() );
			}
		}
		else {
			if ( $identity = $ssh->createBackupAccount($addr[0]) ) {
				if ( $dbconnect->updateIP( $addr[1], $addr[0], $identity ))
					$this->logger->log("The update has been successful." );
				else
					$this->logger->log("The update IP '" . $addr[1] . "' database error.", $this->logger->setError() );
			}
		}
	}
	
}