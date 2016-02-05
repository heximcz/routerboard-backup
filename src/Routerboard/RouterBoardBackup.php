<?php

namespace Src\RouterBoard;

use Src\RouterBoard\SSHConnector;

class RouterBoardBackup extends AbstractRouterBoard implements IRouterBoardBackup {
	
	public function __construct($config, $logger) {
		parent::__construct($config, $logger);
	}
	
	public function backupAllRouterBoards() {
		$db = new $this->config['database']['data-adapter']($this->config, $this->logger);
		if ( $result = $db->getIP() ) {
			$rb = new SSHConnector($this->config, $this->logger);
			foreach ($result as $data) {
				$rb->getBackupFile($data['addr'], $data['identity']);
			}
		}
		else {
			$this->logger->log("Get IP addresses from the database failed! Backup is not available. Try later.", $this->logger->setError() );
		}
		if ( $this->config['mail']['sendmail'] && $this->logger->isMail() )
			$this->logger->send($this->config['mail']['email-from'], $this->config['mail']['email-to']);
	}
	
}