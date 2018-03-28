<?php
namespace RBTests;

use Symfony\Component\Console\Application;
use App\Console\CliRouterBoardModify;
use Symfony\Component\Console\Tester\CommandTester;

class ModTest extends RBCaseTest {

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

    public function testRunCommandModUpdate() {
        $application = new Application ();
        $application->add ( new CliRouterBoardModify( self::$config ) );
        $command = $application->find ( 'rb:mod' );
        $commandTester = new CommandTester ( $command );
        // update
        $commandTester->execute ( array (
            'action' => 'update',
            '-i'  => ['192.168.1.5','192.168.1.222']
        ) );
        $this->assertRegExp ( '/../', $commandTester->getDisplay () );
    }

    public function testRunCommandModUpdateSameIp() {
        $application = new Application ();
        $application->add ( new CliRouterBoardModify( self::$config ) );
        $command = $application->find ( 'rb:mod' );
        $commandTester = new CommandTester ( $command );
        // update
        $commandTester->execute ( array (
            'action' => 'update',
            '-i'  => ['192.168.1.5','192.168.1.5']
        ) );
        $this->assertContains('Both IP addresses already exist in database!', $commandTester->getDisplay () );
    }

    public function testRunCommandModAddNew() {
		$application = new Application ();
		$application->add ( new CliRouterBoardModify( self::$config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		// addnew
		$commandTester->execute ( array (
				'action' => 'addnew',
				'-i'  => ['192.168.1.21']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

	public function testRunCommandModDelete() {
		$application = new Application ();
		$application->add ( new CliRouterBoardModify( self::$config ) );
		$command = $application->find ( 'rb:mod' );
		$commandTester = new CommandTester ( $command );
		// delete
		$commandTester->execute ( array (
				'action' => 'delete',
				'-i'  => ['192.168.1.1']
		) );
		$this->assertRegExp ( '/../', $commandTester->getDisplay () );
	}

    public function testRunCommandModFakeAddNew() {
        $application = new Application ();
        $application->add ( new CliRouterBoardModify( self::$config ) );
        $command = $application->find ( 'rb:mod' );
        $commandTester = new CommandTester ( $command );
        // delete
        $commandTester->execute ( array (
            'action' => 'addnew',
            '-i'  => ['fake']
        ) );
        $this->assertContains( 'Error: Input array is empty!', $commandTester->getDisplay () );
    }

    public function testRunCommandModFakeDelete() {
        $application = new Application ();
        $application->add ( new CliRouterBoardModify( self::$config ) );
        $command = $application->find ( 'rb:mod' );
        $commandTester = new CommandTester ( $command );
        // delete
        $commandTester->execute ( array (
            'action' => 'delete',
            '-i'  => ['fake']
        ) );
        $this->assertContains( 'Error: Input array is empty!', $commandTester->getDisplay () );
    }

    public function testRunCommandModFakeUpdate() {
        $application = new Application ();
        $application->add ( new CliRouterBoardModify( self::$config ) );
        $command = $application->find ( 'rb:mod' );
        $commandTester = new CommandTester ( $command );
        // delete
        $commandTester->execute ( array (
            'action' => 'update',
            '-i'  => ['fake']
        ) );
        $this->assertContains( 'Error: Input array is empty!', $commandTester->getDisplay () );
    }


}
