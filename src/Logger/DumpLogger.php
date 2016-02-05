<?php 
namespace Src\Logger;

class DumpLogger implements ILogger{
	public function log($message, $level = self::LEVEL_INFO){
		var_dump(sprintf('%1$s [%2$s]: %3$s', 
			$level, date("H:i:s"), $message));
	}
}