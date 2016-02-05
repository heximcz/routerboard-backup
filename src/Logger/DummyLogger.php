<?php 
namespace Src\Logger;

class DummyLogger implements ILogger{
	public function log($message, $level = self::LEVEL_INFO){

	}
}