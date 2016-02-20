<?php

namespace App\Config;

use Symfony\Component\Yaml\Parser;
use Exception;

class GetYAMLConfig {

	private $defautConfigPath;
	private $customConfigPath;
	private $config;
	
	public function __construct() {
		$this->defautConfigPath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.default.yml';
		$this->customConfigPath = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.yml';
		$this->createConfig ();
	}
	
	public function getConfigData() {
		return $this->config;
	}
	
	private function parseConfig($configPath) {
		$yaml = new Parser ();
		return $yaml->parse ( file_get_contents ( $configPath ) );
	}
	
	private function createConfig() {
		if ( file_exists ( $this->defautConfigPath ) ) {
			$defconf = $this->parseConfig ( $this->defautConfigPath );
			if ( file_exists ( $this->customConfigPath ) ) {
				$customconf = $this->parseConfig ( $this->customConfigPath );
				$this->config = array_replace_recursive ( $defconf, $customconf );
				return;
			}
			$this->config = $defconf;
			return;
		}
		throw new Exception ( get_class ( $this ) . ' FATAL ERROR: config.default.yml no exist!');
	}
	
}
