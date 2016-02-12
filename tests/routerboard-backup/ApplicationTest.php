<?php
use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardBackup;
use Src\Logger\OutputLogger;
use Src\RouterBoard\IPValidator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\NullOutput;
use Dibi\Connection;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	protected $config;
	
	public function setUp() {
		$myConfig = new GetYAMLConfig ();
		$this->config = $myConfig->getConfigData ();
	}
	
	public function testConfig() {
		$this->assertTrue ( is_array ( $this->config ) );
	}
	
	public function testExecuteMod() {
		$application = new Application ();
		$application->add ( new CliRouterBoardModify ( $this->config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testExecuteBackup() {
		$application = new Application ();
		$application->add ( new CliRouterBoardBackup ( $this->config ) );
		$command = $application->find ( 'rb:backup' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testExecuteList() {
		$application = new Application ();
		$application->add ( new CliRouterBoardList ( $this->config ) );
		$command = $application->find ( 'rb:list' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => $command->getName () 
		) );
		$this->assertRegExp ( '/.../', $commandTester->getDisplay () );
	}
	
	public function testRunCommandList() {
		$this->setUpDatabase ();
		$application = new Application ();
		$application->add ( new CliRouterBoardList ( $this->config ) );
		$command = $application->find ( 'rb:list' );
		$commandTester = new CommandTester ( $command );
		$commandTester->execute ( array (
				'action' => 'list' 
		) );
		
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

	public function testRunCommandsMod() {
		$this->setUpDatabase ();
		$application = new Application ();
		$application->add ( new CliRouterBoardModify( $this->config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		// update
		$commandTester->execute ( array (
				'action' => 'update',
				'-i'  => ['192.168.1.1','192.168.1.2']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		// addnew
		$commandTester->execute ( array (
				'action' => 'addnew',
				'-i'  => ['192.168.1.3']
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
		$this->setUpDatabase ();
		$application = new Application ();
		$application->add ( new CliRouterBoardModify( $this->config ) );
		$command = $application->find ( 'rb:backup' );
		$commandTester = new CommandTester ( $command );
		// backup one
		$commandTester->execute ( array (
				'action' => 'backup',
				'-i'  => ['192.168.1.1']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		// backupall
		$commandTester->execute ( array (
				'action' => 'backup',
		) );
	}
	
	public function testIPAddr() {
		$ip = new IPValidator( $this->config, new OutputLogger( new NullOutput() ) );
		$this->assertTrue( $ip->ipv4validator('192.168.1.254') );
		$this->assertFalse( $ip->ipv4validator('192.168.1.256') );
	}
	
	protected function setUpDatabase() {
		$options = array (
				'driver' => $this->config ['database'] ['driver'],
				'host' => $this->config ['database'] ['host'],
				'username' => $this->config ['database'] ['user'],
				'database' => $this->config ['database'] ['database'],
				'password' => $this->config ['database'] ['password'],
				'charset' => $this->config ['database'] ['charset'],
				'port' => $this->config ['database'] ['port'],
				'persistent' => $this->config ['database'] ['persistent'],
				'dsn' => $this->config ['database'] ['dsn'] 
		);
		$connection = new Connection ( $options );
		$connection->query ( "CREATE TABLE IF NOT EXISTS [routers] (
  					[id] int(11) NOT NULL AUTO_INCREMENT,
  					[addr] char(15) COLLATE utf8_bin NOT NULL,
  					[identity] varchar(255) COLLATE utf8_bin DEFAULT NULL,
  					[created] datetime NOT NULL,
  					[modify] datetime DEFAULT NULL,
  					[lastbackup] datetime DEFAULT NULL,
					PRIMARY KEY ([id])
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
					" );
		$args = [ 
				'addr' => '192.168.1.1',
				'identity' => 'RB-Test',
				'created' => new \DateTime () 
		];
		$connection->query ( 'INSERT INTO [routers]', $args );
	}

}
