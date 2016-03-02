<?php
namespace RBTests;

use Src\Logger\OutputLogger;
use Src\RouterBoard\InputParser;
use Symfony\Component\Console\Output\NullOutput;

class InputParserTest extends RBCaseTest {
	
	public function testInputParser() {
		$input = array(
				0 => '10.17.254.254:2345',
				1 => '10.10.10.10',
				2 => '192.168.1.1:2575'
		);
		$inputParser = new InputParser( self::$config, new OutputLogger( new NullOutput() ), $input );
		$this->assertTrue( $inputParser->getAddr() );
	}

}
