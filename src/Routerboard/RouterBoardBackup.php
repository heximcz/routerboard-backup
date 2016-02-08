<?php

namespace Src\RouterBoard;

use Src\RouterBoard\SSHConnector;

class RouterBoardBackup extends AbstractRouterBoard implements IRouterBoardBackup {
	
	/**
	 * @see \Src\RouterBoard\IRouterBoardBackup::backupAllRouterBoards()
	 */
	public function backupAllRouterBoards() {
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		if ( $result = $db->getIP() ) {
			$rb = new SSHConnector($this->config, $this->logger);
			foreach ($result as $data) {
				$rb->getBackupFile($data['addr'], $data['identity']);
			}
		}
		else {
			$this->logger->log('Get IP addresses from the database failed! Backup is not available. Try later.', $this->logger->setError() );
		}
		$this->sendMail();
	}
	
	/**
	 * @see \Src\RouterBoard\IRouterBoardBackup::backupOneRouterBoard()
	 */
	public function backupOneRouterBoard(array $addr) {
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		$rb = new SSHConnector($this->config, $this->logger);
		foreach ($addr as $ip) {
			if ( $db->checkExistIP($ip) ) {
				$data = $db->getOneIP($ip);
				$rb->getBackupFile( $data[0]['addr'], $data[0]['identity'] );
			}
			else {
				$this->logger->log('IP addresses: ' . $ip . ' does not exist in the database! Add this IP address first.', $this->logger->setError() );
			}
		}
		$this->sendMail();
	}

	/**
	 * Send email with error if any
	 */
	private function sendMail() {
		if ( $this->config['mail']['sendmail'] && $this->logger->isMail() )
			$this->logger->send($this->config['mail']['email-from'], $this->config['mail']['email-to']);
	}

}

