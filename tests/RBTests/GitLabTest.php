<?php
namespace RBTests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\Console\CliRouterBoardGitLab;
use Gitlab\Exception\RuntimeException;

class GitLabTest extends RBCaseTest {

	/**
	 * test only exception
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
			$this->assertContains('Host can not be empty', $e->getMessage());
		}
	}
}