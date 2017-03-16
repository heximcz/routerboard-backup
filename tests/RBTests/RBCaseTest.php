<?php
namespace RBTests;

use App\Config\GetYAMLConfig;
use Src\Adapters\RouterBoardDBAdapter;
use Src\Logger\OutputLogger;
use Symfony\Component\Console\Output\NullOutput;
use Dibi\Connection;
use PHPUnit\Framework\TestCase;

abstract class RBCaseTest extends \PHPUnit_Framework_TestCase {
	
	protected static $db;
	protected static $config;
	
	public static function setUpBeforeClass() {
		$myConfig = new GetYAMLConfig ();
		self::$config = $myConfig->getConfigData ();
		$config = array (
				'driver' => self::$config ['database'] ['driver'],
				'host' => self::$config ['database'] ['host'],
				'username' => self::$config ['database'] ['user'],
				'database' => self::$config ['database'] ['database'],
				'password' => self::$config ['database'] ['password'],
				'charset' => self::$config ['database'] ['charset'],
				'port' => self::$config ['database'] ['port'],
				'persistent' => self::$config ['database'] ['persistent'],
				'dsn' => self::$config ['database'] ['dsn']
		);
		self::$db = new Connection ( $config );
		self::$db->query ( "CREATE TABLE IF NOT EXISTS [routers] (
  					[id] int(11) NOT NULL AUTO_INCREMENT,
  					[addr] char(15) COLLATE utf8_bin NOT NULL,
					[port] smallint(5) UNSIGNED DEFAULT NULL,
					[identity] varchar(255) COLLATE utf8_bin DEFAULT NULL,
  					[created] datetime NOT NULL,
  					[modify] datetime DEFAULT NULL,
  					[lastbackup] datetime DEFAULT NULL,
					PRIMARY KEY ([id])
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
					" );
		$db = new RouterBoardDBAdapter(self::$config, new OutputLogger( new NullOutput() ) );
		for ($i=1; $i<11; $i++) {
			$db->addIP('192.168.1.' . $i, '2345', 'RB-Test' . $i);
		}
	}
	
	
	public static function tearDownAfterClass()
	{
		self::$db->query ( "DROP TABLE [routers]" );
	}
	
}