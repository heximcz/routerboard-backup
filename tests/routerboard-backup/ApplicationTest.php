<?php
use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardBackup;
use Src\Logger\OutputLogger;
use Src\RouterBoard\IPValidator;
use Src\Adapters\RouterBoardDBAdapter;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\NullOutput;
use Dibi\Connection;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	protected static $config;
	protected static $db;
	
	public function testConfig() {
		$config = new GetYAMLConfig ();
		$this->assertTrue ( is_array ( $config->getConfigData () ) );
	}
	
	public function testExecuteMod() {
		$application = new Application ();
		$application->add ( new CliRouterBoardModify ( self::$config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testExecuteBackup() {
		$application = new Application ();
		$application->add ( new CliRouterBoardBackup ( self::$config ) );
		$command = $application->find ( 'rb:backup' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testExecuteList() {
		$application = new Application ();
		$application->add ( new CliRouterBoardList ( self::$config ) );
		$command = $application->find ( 'rb:list' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testRunCommandList() {
		$application = new Application ();
		$application->add ( new CliRouterBoardList ( self::$config ) );
		$command = $application->find ( 'rb:list' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => 'list' 
		) );
		
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

	public function testRunCommandsMod() {
		$application = new Application ();
		$application->add ( new CliRouterBoardModify( self::$config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		// update
		$commandTester->execute ( array (
				'action' => 'update',
				'-i'  => ['192.168.1.5','192.168.1.20']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		// addnew
		$commandTester->execute ( array (
				'action' => 'addnew',
				'-i'  => ['192.168.1.21']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		// delete
		$commandTester->execute ( array (
				'action' => 'delete',
				'-i'  => ['192.168.1.1']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

	public function testRunCommandsBackup() {
		$application = new Application ();
		$application->add ( new CliRouterBoardBackup( self::$config ) );
		$command = $application->find ( 'rb:backup' );
		$commandTester = new CommandTester ( $command );
		// backup one
		$commandTester->execute ( array (
				'action' => 'backup',
				'-i'  => ['192.168.1.7']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		// backupall
		$commandTester->execute ( array (
				'action' => 'backup',
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		
	}
	
	public function testIPAddr() {
		$ip = new IPValidator( self::$config, new OutputLogger( new NullOutput() ) );
		$this->assertTrue( $ip->ipv4validator('192.168.1.254') );
		$this->assertFalse( $ip->ipv4validator('192.168.1.256') );
	}
	
	public static function setUpBeforeClass() {
		$myConfig = new GetYAMLConfig ();
		self::$config = $myConfig->getConfigData ();
		$config = array (
				'driver' => self::$config ['database'] ['driver'],
				'host' => self::$config ['database'] ['host'],
				'username' => self::$config ['database'] ['user'],
				'database' => self::$config ['database'] ['database'],
				'password' => self::$config ['database'] ['password'],
				'charset' => self::$config ['database'] ['charset'],
				'port' => self::$config ['database'] ['port'],
				'persistent' => self::$config ['database'] ['persistent'],
				'dsn' => self::$config ['database'] ['dsn'] 
		);
		self::$db = new Connection ( $config );
		self::$db->query ( "CREATE TABLE IF NOT EXISTS [routers] (
  					[id] int(11) NOT NULL AUTO_INCREMENT,
  					[addr] char(15) COLLATE utf8_bin NOT NULL,
  					[identity] varchar(255) COLLATE utf8_bin DEFAULT NULL,
  					[created] datetime NOT NULL,
  					[modify] datetime DEFAULT NULL,
  					[lastbackup] datetime DEFAULT NULL,
					PRIMARY KEY ([id])
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
					" );
		$db = new RouterBoardDBAdapter(self::$config, new OutputLogger( new NullOutput() ));
		for ($i=1; $i<11; $i++) {
			$db->addIP('192.168.1.' . $i, 'RB-Test' . $i);
		}
	}

	
	public static function tearDownAfterClass()
	{
		self::$db->query ( "DROP TABLE [routers]" );
	}
	
}
