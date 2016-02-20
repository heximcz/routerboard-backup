<?php
namespace RBTests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Console\CliRouterBoardGitLab;
use Gitlab\Exception\RuntimeException;

class GitLabTest extends RBCaseTest {

	/*
	public function testEmpty()
	{
		$stack = array();
		$this->assertEmpty($stack);

	}
*/	
	public function testExecuteGitLabBackup() {
		try {
			$application = new Application ();
			$application->add ( new CliRouterBoardGitLab ( self::$config ) );
			$command = $application->find ( 'rb:gitlab' );
			$commandTester = new CommandTester ( $command );
			$commandTester->execute ( array (
				'action' => 'backup',
				'--help' => true
			) );
			$this->assertRegExp ( '/../', $commandTester->getDisplay () );
		} 
		catch (RuntimeException $e) {
			$this->assertContains('resolve host', $e->getMessage());
		}
	}
/*
	public function testRunCommandsGitLabBackupOne() {
		$application = new Application ();
		$application->add ( new CliRouterBoardGitLab( self::$config ) );
		$command = $application->find ( 'rb:gitlab' );
		$commandTester = new CommandTester ( $command );
		// backup one
		$commandTester->execute ( array (
				'action' => 'backup',
				'-i'  => ['192.168.1.7']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

	public function testRunCommandsGitLabBackupAll() {
		$application = new Application ();
		$application->add ( new CliRouterBoardGitLab( self::$config ) );
		$command = $application->find ( 'rb:gitlab' );
		$commandTester = new CommandTester ( $command );
		// backupall
		$commandTester->execute ( array (
				'action' => 'backup',
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	
	}
*/	
}