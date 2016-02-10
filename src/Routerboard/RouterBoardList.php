<?php

namespace Src\RouterBoard;

use Src\Adapters\RouterBoardDBAdapter;

class RouterBoardList extends AbstractRouterBoard {
	
	/**
	 * Print all info about routers from backup list
	 */
	public function printAllRouterBoards() {
		$db = new RouterBoardDBAdapter( $this->config, $this->logger );
		if ( $result = $db->getIP() ) {
			foreach ($result as $data) {
				echo ($data['identity'] . ' - ' . $data['addr'] . PHP_EOL);
			}
		}
		else {
			$this->logger->log('Get IP addresses from the database failed! Print is not available. Try later.', $this->logger->setError() );
		}
	}


}

