<?php

namespace Src\RouterBoard;

interface IRouterBoardBackup {
	
	/**
	 * Backup all IP address from backup list in the database
	 */
	public function backupAllRouterBoards();
	
	/**
	 * Backup selected IP addresses
	 * @param array $addr
	 */
	public function backupOneRouterBoard(array $addr);

}
