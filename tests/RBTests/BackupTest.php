<?php
namespace RBTests;

use Symfony\Component\Console\Application;
use App\Console\CliRouterBoardBackup;
use Symfony\Component\Console\Tester\CommandTester;

class BackupTest extends RBCaseTest {

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

	public function testRunCommandsBackupOne() {
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
	}

	public function testRunCommandsBackupAll() {
		$application = new Application ();
		$application->add ( new CliRouterBoardBackup( self::$config ) );
		$command = $application->find ( 'rb:backup' );
		$commandTester = new CommandTester ( $command );
		// backupall
		$commandTester->execute ( array (
				'action' => 'backup',
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	
	}

    public function testRunCommandsBackupOneFakeInput() {
        $application = new Application ();
        $application->add ( new CliRouterBoardBackup( self::$config ) );
        $command = $application->find ( 'rb:backup' );
        $commandTester = new CommandTester ( $command );
        // backup one
        $commandTester->execute ( array (
            'action' => 'backup',
            '-i'  => ['fake']
        ) );
        $this->assertContains( 'Input array is empty', $commandTester->getDisplay() );
    }

}