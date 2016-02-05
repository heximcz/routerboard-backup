<?php 
namespace Src\Logger;

class EchoLogger implements ILogger{
	public function log($message, $level = self::LEVEL_INFO){
		echo sprintf('%1$s [%2$s]: %3$s', 
			$level, date("H:i:s"), $message);
	}
}