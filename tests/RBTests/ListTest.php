<?php
namespace RBTests;

use Symfony\Component\Console\Application;
use App\Console\CliRouterBoardList;
use Symfony\Component\Console\Tester\CommandTester;

class ListTest extends RBCaseTest {

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
	
}