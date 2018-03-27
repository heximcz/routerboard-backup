<?php

namespace Src\RouterBoard;

use Exception;
use Src\Logger\OutputLogger;

class RouterBoardBackup extends AbstractRouterBoard implements IRouterBoardBackup
{

    private $dbconnect;
    private $ssh;
    private $filename;

    /**
     * RouterBoardBackup constructor.
     * @param array $config
     * @param OutputLogger $logger
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        parent::__construct($config, $logger);
        $this->dbconnect = new $this->config['database']['data-adapter']($this->config, $this->logger);
		$this->ssh = new SSHConnector($this->config, $this->logger);
		$this->filename = $this->config['routerboard']['backupuser'] . '-' . date("Ymdhis", time());
		
	}

    /**
     * @see \Src\RouterBoard\IRouterBoardBackup::backupAllRouterBoards()
     */
    public function backupAllRouterBoards()
    {
        if ($result = $this->dbconnect->getIP()) {
            foreach ($result as $data) {
                if (!is_null($data['port'])) {
                    $this->goBackup($data['addr'], $data['port'], $data['identity']);
                    continue;
                }
                $this->goBackup($data['addr'], $this->config['routerboard']['ssh-port'], $data['identity']);
            }
            return;
        }
        $this->logger->log('Get IP addresses from the database failed! Backup is not available. Try later.', $this->logger->setError());
        $this->sendMail();
    }

    /**
     * @see \Src\RouterBoard\IRouterBoardBackup::backupOneRouterBoard()
     */
    public function backupOneRouterBoard(InputParser $input)
    {
        if (!$inputArray = $input->getAddr())
            throw new Exception("Input array is empty!");

        foreach ($inputArray as $ipAddr) {
            if ($this->dbconnect->checkExistIP($ipAddr['addr'])) {
                $data = $this->dbconnect->getOneIP($ipAddr['addr']);
                $this->goBackup($data[0]['addr'], $data[0]['port'], $data[0]['identity']);
                continue;
            }
            $this->logger->log('IP addresses: ' . $ipAddr['addr'] . ' does not exist in the database! Add this IP address first.', $this->logger->setError());
        }
        $this->sendMail();
    }

    private function goBackup($addr, $port, $identity)
    {
        if ($this->ssh->getBackupFile($addr, $port, $this->filename, $this->config['system']['backupdir'], $identity)) {
            $this->logger->log("Backup of the router " . $addr . " has been sucessfully.");
            $this->dbconnect->updateBackupTime($addr);
            return;
        }
        $this->logger->log("Backup of the router " . $addr . " has not been sucessfully.", $this->logger->setError());
        return;
    }

    /**
     * Send email with error if any
     */
    private function sendMail()
    {
        if ($this->config['mail']['sendmail'] && $this->logger->isMail())
            $this->logger->send($this->config['mail']['email-from'], $this->config['mail']['email-to']);
    }

}

