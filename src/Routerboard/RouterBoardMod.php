<?php

namespace Src\RouterBoard;

use Src\RouterBoard\IPValidator;
use Src\RouterBoard\SSHConnector;

class RouterBoardMod extends AbstractRouterBoard implements IRouterBoardMod {
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::addNewIP()
	 */
	public function addNewIP(array $ip) {
		$validator = new IPValidator($this->config, $this->logger);
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		$user = new SSHConnector($this->config, $this->logger);

		foreach ($ip as $addr) {
			// is ip addr valid ?
			if ( $validator->ipv4validator($addr) ) {
				if ( !$db->checkExistIP($addr) ) {
				// create backup user and on success add ip to backup list in db
					if ( $identity = $user->createBackupAccount($addr) ) {
						if ( $db->addIP($addr, $identity) )
							$this->logger->log( "The router: '" . $identity . "'@'" . $addr . "' has been successfully added to database." );
					}
				}
				else 
					$this->logger->log( "The IP address " . $addr . " already exists in the database!", $this->logger->setError() );
			}
		}
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::deleteIP()
	 */
	public function deleteIP(array $ip) {
		$validator = new IPValidator($this->config, $this->logger);
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		foreach ($ip as $addr) {
			if ( $validator->ipv4validator($addr) ) {
				if ( $db->deleteIP($addr) ) 
					$this->logger->log( "The IP '" .$addr . "' has been deleted successfully.");
				else
					$this->logger->log( "The delete of the IP '" .$addr . "' from database fails.", $this->logger->setError() );
			}
				
		}
		
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoard::updateIP()
	 */
	public function updateIP(array $ip) {
		if ( count($ip) != 2 ) {
			$this->logger->log( "The delete is not possible. Enter only two IP addresses: -i ip -i ip",$this->logger->setError() );
			return;
		}
		$validator = new IPValidator($this->config, $this->logger);
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		foreach ($ip as $addr) {
			if ( !$validator->ipv4validator($addr) )
				return;
		}
		$ip0 = $db->checkExistIP($ip[0]);
		$ip1 = $db->checkExistIP($ip[1]);
		if ( $ip0 && $ip1 ) {
			$this->logger->log("Both IP addresses already exist in database!",$this->logger->setError() );
		}
		elseif ( !$ip0 && !$ip1 )
		{
			$this->logger->log("Neither of the two IP address exist in the database!",$this->logger->setError() );
		}
		else {
			$user = new SSHConnector($this->config, $this->logger);
			if ($ip0) {
				if ( $identity = $user->createBackupAccount($ip[1]) ) {
					if ( $db->updateIP( $ip[0], $ip[1], $identity ))
						$this->logger->log("The update has been successful." );
					else
						$this->logger->log("The update IP '" . $ip[0] . "' database error.", $this->logger->setError() );
				}
			}
			else {
				if ( $identity = $user->createBackupAccount($ip[0]) ) {
					if ( $db->updateIP( $ip[1], $ip[0], $identity ))
						$this->logger->log("The update has been successful." );
					else
						$this->logger->log("The update IP '" . $ip[1] . "' database error.", $this->logger->setError() );
				}
			}
		}
	}
	
}