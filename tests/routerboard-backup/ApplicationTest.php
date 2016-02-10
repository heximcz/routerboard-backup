<?php 

use App\Config\GetYAMLConfig;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	public function testConfig() {
		$myConfig = new GetYAMLConfig();
		$config   = $myConfig->getConfigData();
		$this->assertTrue( is_array($config) );
	}

}