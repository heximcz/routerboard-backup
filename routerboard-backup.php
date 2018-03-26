<?php
use Symfony\Component\Console\Application;
use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardBackup;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardGitLab;
use App\Console\CliRouterBoardDecode;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'vendor/autoload.php';

try {
	$myConfig = new GetYAMLConfig();
	$config   = $myConfig->getConfigData();
	$application = new Application( "Mikrotik RouterBoard Backup:","1.0.2" );
	$application->add( new CliRouterBoardModify( $config ) );
	$application->add( new CliRouterBoardBackup( $config ) );
	$application->add( new CliRouterBoardList( $config ) );
    $application->add( new CliRouterBoardGitLab( $config ) );
    $application->add( new CliRouterBoardDecode( $config ) );
	$application->run();
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
