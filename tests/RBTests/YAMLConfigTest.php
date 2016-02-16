<?php
namespace RBTests;

use App\Config\GetYAMLConfig;

class YamlConfigTest extends RBCaseTest {
	
	public function testConfig() {
		$config = new GetYAMLConfig ();
		$this->assertTrue ( is_array ( $config->getConfigData () ) );
	}

}
