<?php
namespace Src\Adapters;

use dibi;
use Src\Logger\OutputLogger;

class RouterBoardDBAdapter extends AbstractDataAdapter
{

    /*
    --
    -- MySQL default table
    --
    CREATE TABLE IF NOT EXISTS `routers` (
      `id` int(11) NOT NULL,
      `addr` char(15) COLLATE utf8_bin NOT NULL COMMENT 'IP address',
      `identity` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'System identity',
      `created` datetime NOT NULL,
      `modify` datetime DEFAULT NULL,
      `lastbackup` datetime DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

    ALTER TABLE `routers`
      ADD PRIMARY KEY (`id`);

    ALTER TABLE `routers`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
    */

    /**
     * RouterBoardDBAdapter constructor.
     * @param array $config
     * @param OutputLogger $logger
     * @throws \Exception
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        parent::__construct($config, $logger);
        $options = array(
            'driver' => $this->config['database']['driver'],
            'host' => $this->config['database']['host'],
            'username' => $this->config['database']['user'],
            'database' => $this->config['database']['database'],
            'password' => $this->config['database']['password'],
            'charset' => $this->config['database']['charset'],
            'port' => $this->config['database']['port'],
            'persistent' => $this->config['database']['persistent'],
            'dsn' => $this->config['database']['dsn']
        );
        dibi::connect($options, 'rbdb');
    }

    /**
     * @see \Src\Adapters\IAdapter::addIP()
     */
    public function addIP($addr, $port, $identity)
    {
        $args = [
            'addr' => $addr,
            'port' => $port,
            'identity' => $identity,
            'created' => new \DateTime()
        ];
        if (!$this->checkExistIP($addr)) {
            // add ip to db
            if (dibi::query('INSERT INTO [routers]', $args))
                return true;
            return false;
        }
    }

    /**
     * @see \Src\Adapters\IAdapter::getIP()
     */
    public function getIP()
    {
        if ($result = dibi::query('SELECT [id], [addr], [port], [identity] FROM [routers]'))
            return $result->fetchAssoc('id');
        return false;
    }

    /**
     * @see \Src\Adapters\IAdapter::getOneIP()
     */
    public function getOneIP($addr)
    {
        if ($result = dibi::query('SELECT [id], [addr], [port], [identity] FROM [routers] WHERE [addr]=%s', $addr))
            return $result->fetchAll();
        return false;
    }

    /**
     * @see \Src\Adapters\IAdapter::updateIP()
     */
    public function updateIP($oldAddr, $newAddr, $newPort, $identity)
    {
        $args = [
            'addr' => $newAddr,
            'port' => $newPort,
            'identity' => $identity,
            'modify' => new \DateTime()
        ];
        if (dibi::query('UPDATE [routers] SET ', $args, 'WHERE [addr]=%s', $oldAddr))
            return true;
        return false;
    }

    /**
     * @see \Src\Adapters\IAdapter::deleteIP()
     */
    public function deleteIP($addr)
    {
        if (dibi::query('DELETE FROM [routers] WHERE [addr]=%s', $addr))
            return true;
        return false;
    }


    /**
     * @see \Src\Adapters\IAdapter::checkExistIP()
     */
    public function checkExistIP($addr)
    {
        if (dibi::fetchSingle('SELECT [id] FROM [routers] WHERE [addr] = %s', $addr))
            return true;
        return false;
    }

    /**
     * @see \Src\Adapters\IAdapter::updateBackupTime()
     */
    public function updateBackupTime($addr)
    {
        if ($this->checkExistIP($addr)) {
            $args = [
                'lastbackup' => new \DateTime()
            ];
            if (dibi::query('UPDATE [routers] SET ', $args, 'WHERE [addr]=%s', $addr))
                return true;
            return false;
        }
        return false;
    }
}

