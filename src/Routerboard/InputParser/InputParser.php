<?php

namespace Src\RouterBoard;

use Src\Logger\OutputLogger;
use Symfony\Component\Console\Input\InputInterface;

class InputParser extends AbstractRouterBoard
{

    /** @var array $pAddr Parsed IP Address */
    private $pAddr = array();
    /** @var IPValidator $validate */
    private $validate;

    /**
     * InputParser constructor.
     * @param array $config
     * @param OutputLogger $logger
     * @param array $input
     */
    public function __construct(array $config, OutputLogger $logger, array $input)
    {
        parent::__construct($config, $logger);
        $this->validate = new IPValidator($config, $logger);
        $this->inputParserArray($input);
    }

    /**
     * Parsed ip address and SSH ports
     * @return mixed (array, false)
     */
    public function getAddr()
    {
        if (!empty($this->pAddr))
            return $this->pAddr;
        return false;
    }

    /**
     * Parse input IP address parameters and check different SSH port
     * @param array $input
     */
    private function inputParserArray(array $input)
    {
        $i = 0;
        foreach ($input as $addr) {
            if (strpos($addr, ':') === false) {
                $this->pAddr[$i]['addr'] = $addr;
                $this->pAddr[$i++]['port'] = $this->config['routerboard']['ssh-port'];
                continue;
            }
            $parse = explode(':', $addr, 2);
            $this->pAddr[$i]['addr'] = $parse[0];
            $this->pAddr[$i++]['port'] = $parse[1];
        }
        $this->pAddr = array_filter($this->pAddr, array($this, "validateParsedAddress"));
    }

    /**
     * Check if domain or IP address is valid and port range is between 1-65535
     * @param array $addr
     * @return boolean
     */
    private function validateParsedAddress($addr)
    {
        if ($this->validate->ipv4validator($addr['addr'])) {
            if ($addr['port'] > 0 && $addr['port'] < 65536)
                return true;
            $this->logger->log("SSH port in: '" . $addr['addr'] . ':' . $addr['port'] . "' is out of range 1-65535 !", $this->logger->setError());
        }
        return false;
    }

}