<?php
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Exception\IOException;
use App\Config\GetYAMLConfig;
use App\Console\CliRouterBoardModify;
use App\Console\CliRouterBoardBackup;
use App\Console\CliRouterBoardList;
use App\Console\CliRouterBoardGitLab;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'vendor/autoload.php';

try {
	$myConfig = new GetYAMLConfig();
	$config   = $myConfig->getConfigData();
	$application = new Application( "Mikrotik RouterBoard tools:","0.9.1" );
	$application->add( new CliRouterBoardModify( $config ) );
	$application->add( new CliRouterBoardBackup( $config ) );
	$application->add( new CliRouterBoardList( $config ) );
	$application->add( new CliRouterBoardGitLab( $config ) );
	$application->run();
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
} catch (IOException $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}