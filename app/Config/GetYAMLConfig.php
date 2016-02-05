<?php
namespace App\Config;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Exception;

class GetYAMLConfig {
	
	private $defautConfigPath;
	private $customConfigPath;
	private $config;

	public function __construct() {
		$this->defautConfigPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.default.yml';
		$this->customConfigPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config.yml';
		$this->createConfig();
	}
	
	public function getConfigData() {
		return $this->config;
	}
	
	private function parseConfig( $configPath ) {
		$yaml = new Parser();
		try {
			return $yaml->parse( file_get_contents( $configPath ) );
		} catch ( ParseException $e ) {
			printf( "Unable to parse the YAML string from config file: %s \n", $e->getMessage() );
			die();
		}		
	}
	
	private function createConfig() {
	    try {
    		if ( file_exists( $this->defautConfigPath ) ) {
	       		$defconf = $this->parseConfig( $this->defautConfigPath );
			    if ( file_exists( $this->customConfigPath ) ) {
				    $customconf = $this->parseConfig( $this->customConfigPath );
				    $this->config = array_replace_recursive( $defconf, $customconf );
			    }
			    else {
			        $this->config = $defconf;
			    }
    		}
    		else
    		    throw new Exception( get_class($this) . ' FATAL ERROR: config.default.yml no exist!');
    	} catch ( ParseException $e ) {
			printf( "Unable to create config array: %s \n", $e->getMessage() );
			die();
    	}		
	}
	
}
