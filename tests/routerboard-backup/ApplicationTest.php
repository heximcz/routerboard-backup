<?php 

use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardBackup;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ApplicationTest extends \PHPUnit_Framework_TestCase {
	

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
    
}

