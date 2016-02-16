<?php
namespace RBTests;

use Src\Logger\OutputLogger;
use Src\Adapters\RouterBoardDBAdapter;
use Symfony\Component\Console\Output\NullOutput;

class AdapterDBTest extends RBCaseTest {
	
	public function testDBAdapterAddIP() {
		$db = new RouterBoardDBAdapter(self::$config, new OutputLogger( new NullOutput() ));
		for ($i=100; $i<120; $i++) {
			$this->assertTrue( $db->addIP( '192.168.1.' . $i, 'RB-Test' . $i ) );
		}
	}
	
	public function testDBAdapterUpdateIP() {
		$db = new RouterBoardDBAdapter(self::$config, new OutputLogger( new NullOutput() ));
		$this->assertTrue( $db->updateIP( '192.168.1.100', '192.168.1.50', 'RB-Test50' ) );
		$this->assertFalse( $db->updateIP( '192.168.1.70', '192.168.1.71', 'RB-Test111' ) );
	}
	
	public function testDbAdapterUpdateBackupTime() {
		$db = new RouterBoardDBAdapter(self::$config, new OutputLogger( new NullOutput() ));
		$this->assertTrue( $db->updateBackupTime( '192.168.1.110' ) );
		
	}

}
