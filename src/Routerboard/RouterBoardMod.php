<?php

namespace Src\RouterBoard;

use Exception;
use Src\Adapters\RouterBoardDBAdapter;

class RouterBoardMod extends AbstractRouterBoard implements IRouterBoardMod
{

    /**
     * @see \Src\RouterBoard\IRouterBoardMod::addNewIP()
     * @param InputParser $input
     * @throws Exception
     */
    public function addNewIP(InputParser $input)
    {
        $dbClass = $this->config['database']['data-adapter'];
        /** @var RouterBoardDBAdapter $dbConnect */
        $dbConnect = new $dbClass($this->config, $this->logger);
		$user = new SSHConnector($this->config, $this->logger);

		if (!$inputArray = $input->getAddr())
            throw new Exception("Input array is empty!");

		foreach ($inputArray as $ipAddr) {
            if (!$dbConnect->checkExistIP($ipAddr['addr'])) {
                // create backup user and on success add ip to backup list in db
                if ($identity = $user->createBackupAccount($ipAddr['addr'], $ipAddr['port'])) {
                    if ($dbConnect->addIP($ipAddr['addr'], $ipAddr['port'], $identity))
                        $this->logger->log("The router: '" . $identity . "'@'" . $ipAddr['addr'] . ":" . $ipAddr['port'] . "' has been successfully added to database.");
                }
                else
                    throw new Exception("Remote Login to the ".$ipAddr['addr']." Failed!");
            } else
                $this->logger->log("The IP address " . $ipAddr['addr'] . " already exists in the database!", $this->logger->setError());
        }
	}

    /**
     * @see \Src\RouterBoard\IRouterBoard::deleteIP()
     * @param InputParser $input
     * @throws Exception
     */
    public function deleteIP(InputParser $input)
    {
        $dbClass = $this->config['database']['data-adapter'];
        /** @var RouterBoardDBAdapter $dbConnect */
        $dbConnect = new $dbClass($this->config, $this->logger);
		
		if (!$inputArray = $input->getAddr())
            throw new Exception("Input array is empty!");
		
		foreach ($inputArray as $ipAddr) {
            if ($dbConnect->deleteIP($ipAddr['addr'])) {
                $this->logger->log("The IP '" . $ipAddr['addr'] . "' has been deleted successfully.");
                continue;
            }
            $this->logger->log("The delete of the IP '" . $ipAddr['addr'] . "' from database fails.", $this->logger->setError());
        }
		
	}

    /**
     * @see \Src\RouterBoard\IRouterBoard::updateIP()
     * @param InputParser $input
     * @throws Exception
     */
    public function updateIP(InputParser $input)
    {
        if (!$inputArray = $input->getAddr())
            throw new Exception("Input array is empty!");

        if (count($inputArray) != 2) {
            $this->logger->log("The delete is not possible. Enter only two IP addresses: -i ip -i ip", $this->logger->setError());
            return;
        }

        $dbClass = $this->config['database']['data-adapter'];
        /** @var RouterBoardDBAdapter $dbConnect */
        $dbConnect = new $dbClass($this->config, $this->logger);

		$ip0 = $dbConnect->checkExistIP($inputArray[0]['addr']);
		$ip1 = $dbConnect->checkExistIP($inputArray[1]['addr']);
		if ($ip0 && $ip1) {
            $this->logger->log("Both IP addresses already exist in database!", $this->logger->setError());
            return;
        }
		if (!$ip0 && !$ip1) {
            $this->logger->log("Neither of the two IP address exist in the database!", $this->logger->setError());
            return;
        }
		
		$ssh = new SSHConnector($this->config, $this->logger);
		if ($ip0) {
            if ($identity = $ssh->createBackupAccount($inputArray[1]['addr'], $inputArray[1]['port'])) {
                if ($dbConnect->updateIP($inputArray[0]['addr'], $inputArray[1]['addr'], $inputArray[1]['port'], $identity))
                    $this->logger->log("The update has been successful.");
                else
                    $this->logger->log("The update IP '" . $inputArray[0]['addr'] . "' database error.", $this->logger->setError());
            }
            return;
        }
		if ($identity = $ssh->createBackupAccount($inputArray[0]['addr'], $inputArray[0]['port'])) {
            if ($dbConnect->updateIP($inputArray[1]['addr'], $inputArray[0]['addr'], $inputArray[0]['port'], $identity))
                $this->logger->log("The update has been successful.");
            else
                $this->logger->log("The update IP '" . $inputArray[1]['addr'] . "' database error.", $this->logger->setError());
        }
	}

}