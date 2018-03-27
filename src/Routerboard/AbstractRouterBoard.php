<?php

namespace Src\RouterBoard;

use Src\Logger\OutputLogger;

abstract class AbstractRouterBoard
{

    /**
     * @var array $config
     */
    protected $config = array();

    /**
     * @var OutputLogger $logger
     */
    protected $logger;

    /**
     * AbstractRouterBoard constructor.
     * @param array $config
     * @param OutputLogger $logger
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }
}