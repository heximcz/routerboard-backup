<?php 

use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardBackup;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Dibi\Connection;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->setUpDatabase();
	}

	public function testConfig() {
		$myConfig = new GetYAMLConfig();
		$config = $myConfig->getConfigData();
		$this->assertTrue( is_array($config) );
	}

    public function testExecuteMod()
    {
        $application = new Application();
        $myConfig = new GetYAMLConfig();
        $application->add(new CliRouterBoardModify( $myConfig->getConfigData() ) );

        $command = $application->find('rb:mod');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('action' => $command->getName()));
        $this->assertRegExp('/.../', $commandTester->getDisplay());

    }
    
    public function testExecuteBackup()
    {
    	$application = new Application();
    	$myConfig = new GetYAMLConfig();
    	$application->add(new CliRouterBoardBackup( $myConfig->getConfigData() ) );
    
    	$command = $application->find('rb:backup');
    	$commandTester = new CommandTester($command);
    	$commandTester->execute(array('action' => $command->getName()));
    	$this->assertRegExp('/.../', $commandTester->getDisplay());
    
    }

    public function testExecuteList()
    {
    	$application = new Application();
    	$myConfig = new GetYAMLConfig();
    	$application->add(new CliRouterBoardList( $myConfig->getConfigData() ) );
    
    	$command = $application->find('rb:list');
    	$commandTester = new CommandTester($command);
    	$commandTester->execute(array('action' => $command->getName()));
    	$this->assertRegExp('/.../', $commandTester->getDisplay());
    
    }

    public function testRunCommandList()
    {
    	$application = new Application();
    	$myConfig = new GetYAMLConfig();
    	$application->add(new CliRouterBoardList( $myConfig->getConfigData() ) );
    
    	$command = $application->find('rb:list');
    	$commandTester = new CommandTester($command);
    	$commandTester->execute(array(
            'command'      => $command->getName(),
    	));
    
    	$this->assertRegExp('/../', $commandTester->getDisplay());
    }
    
	protected function setUpDatabase() {
		$cnf = new GetYAMLConfig();
		$config = $cnf->getConfigData();
		$options = array(
				'driver'	 => $config['database']['driver'],
				'host'		 => $config['database']['host'],
				'username'	 => $config['database']['user'],
				'database'	 => $config['database']['database'],
				'password'	 => $config['database']['password'],
				'charset'	 => $config['database']['charset'],
				'port'		 => $config['database']['port'],
				'persistent' => $config['database']['persistent'],
				'dsn'		 => $config['database']['dsn']
		);
		$connection = new Dibi\Connection($options);
		$connection->query("CREATE TABLE IF NOT EXISTS [routers] (
  					[id] int(11) NOT NULL AUTO_INCREMENT,
  					[addr] char(15) COLLATE utf8_bin NOT NULL,
  					[identity] varchar(255) COLLATE utf8_bin DEFAULT NULL,
  					[created] datetime NOT NULL,
  					[modify] datetime DEFAULT NULL,
  					[lastbackup] datetime DEFAULT NULL,
					PRIMARY KEY ([id])
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
					");
	}

}

