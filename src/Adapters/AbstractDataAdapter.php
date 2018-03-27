<?php

namespace Src\Adapters;

use Src\Logger\OutputLogger;

abstract class AbstractDataAdapter implements IAdapter
{
    /** @var array $config */
    protected $config = array();

    /** @var OutputLogger $logger */
    protected $logger;

    /**
     * AbstractDataAdapter constructor.
     * @param array $config
     * @param OutputLogger $logger
     */
    public function __construct(array $config, OutputLogger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }
}

