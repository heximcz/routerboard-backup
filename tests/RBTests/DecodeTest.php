<?php
namespace RBTests;

use App\Console\CliRouterBoardDecode;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DecodeTest extends RBCaseTest {

    public function testRunCommandDecode() {
        $text = "ioygpoIYGOIyvciuTFciYGFCD*76rcxYC8u6rdufcU^Rxu";
        $text = base64_encode($text);
        $myfile = fopen("fakebase64.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);

        $application = new Application ();
        $application->add ( new CliRouterBoardDecode( self::$config ) );
        $command = $application->find ( 'rb:decode' );
        $commandTester = new CommandTester ( $command );
        $commandTester->execute ( array (
            '-f'  => './fakebase64.txt'
        ) );
        $this->assertContains( 'INFO: File has been decoded.', $commandTester->getDisplay () );
        foreach(glob("fakebase64.txt*") as $f) {
            unlink($f);
        }
    }

    public function testRunCommandDecodeNoBase64File() {
        $text = "ioygpoIYGOIyvciuTFciYGFCD*76rcxYC8u6rdufcU^Rxu";
        $myfile = fopen("fakebase64.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);

        $application = new Application ();
        $application->add ( new CliRouterBoardDecode( self::$config ) );
        $command = $application->find ( 'rb:decode' );
        $commandTester = new CommandTester ( $command );
        $commandTester->execute ( array (
            '-f'  => './fakebase64.txt'
        ) );
        $this->assertContains( 'File is not a base64 file!', $commandTester->getDisplay () );
        foreach(glob("fakebase64.txt*") as $f) {
            unlink($f);
        }
    }

    public function testRunCommandDecodeFileNoExist() {
        $application = new Application ();
        $application->add ( new CliRouterBoardDecode( self::$config ) );
        $command = $application->find ( 'rb:decode' );
        $commandTester = new CommandTester ( $command );
        $commandTester->execute ( array (
            '-f'  => './fakebase64.txt'
        ) );
        $this->assertContains( 'File does not exist.', $commandTester->getDisplay () );
    }


}
