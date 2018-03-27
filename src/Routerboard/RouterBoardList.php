<?php

namespace Src\RouterBoard;

use Src\Adapters\RouterBoardDBAdapter;

class RouterBoardList extends AbstractRouterBoard
{

    /**
     * Print all info about routers from backup list
     */
    public function printAllRouterBoards()
    {
        $dbClass = $this->config['database']['data-adapter'];
        /** @var RouterBoardDBAdapter $dbConnect */
        $dbConnect = new $dbClass($this->config, $this->logger);

		if ($result = $dbConnect->getIP()) {
            foreach ($result as $data) {
                $this->logger->log($data['identity'] . ' - ' . $data['addr'] . ':' . $data['port'], $this->logger->setNotice());
            }
            return;
        }
		$this->logger->log('Get IP addresses from the database failed! Print is not available. Try later.', $this->logger->setError());
	}

}

