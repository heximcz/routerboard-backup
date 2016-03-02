<?php
namespace RBTests;

use Src\Logger\OutputLogger;
use Src\RouterBoard\IPValidator;
use Symfony\Component\Console\Output\NullOutput;

class IPValidatorTest extends RBCaseTest {
	
	public function testIPAddr() {
		$ip = new IPValidator( self::$config, new OutputLogger( new NullOutput() ) );
		$this->assertTrue( $ip->ipv4validator('192.168.1.254') );
		$this->assertTrue( $ip->ipv4validator('google.com') );
		$this->assertFalse( $ip->ipv4validator('192.168.1.256') );
	}

}
