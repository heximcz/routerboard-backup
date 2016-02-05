<?php

namespace Src\Adapters;

use Src\Logger\OutputLogger;
use Src\Logger\ILogger;

abstract class AbstractDataAdapter implements IAdapter{
	protected $config = array();
	protected $logger;
	
	public function __construct(array $config, OutputLogger $logger){
		$this->config = $config;
		$this->logger = $logger;
	}
}

