<?php

namespace Src\RouterBoard;

use Src\Logger\OutputLogger;

abstract class AbstractRouterBoard {

	protected $config = array();
	protected $logger;
	
	public function __construct(array $config, OutputLogger $logger){
		$this->config = $config;
		$this->logger = $logger;
	}
}