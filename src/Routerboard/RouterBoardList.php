<?php

namespace Src\RouterBoard;

class RouterBoardList extends AbstractRouterBoard
{

    /**
     * Print all info about routers from backup list
     */
    public function printAllRouterBoards()
    {
        $dbconnect = new $this->config['database']['data-adapter']($this->config, $this->logger);
		if ($result = $dbconnect->getIP()) {
            foreach ($result as $data) {
                $this->logger->log($data['identity'] . ' - ' . $data['addr'] . ':' . $data['port'], $this->logger->setNotice());
            }
            return;
        }
		$this->logger->log('Get IP addresses from the database failed! Print is not available. Try later.', $this->logger->setError());
	}


}

