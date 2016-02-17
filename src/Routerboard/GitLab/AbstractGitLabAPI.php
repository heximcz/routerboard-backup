<?php

namespace Src\RouterBoard;

use Src\Logger\OutputLogger;

abstract class AbstractGitLabAPI {

	protected $config;
	protected $logger;
	
	public function __construct( array $config, OutputLogger $logger ) {
		$this->config = $config;
		$this->logger = $logger;
	}
	
}