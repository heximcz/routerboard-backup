<?php 
namespace Src\Logger;

interface ILogger{
	const LEVEL_INFO = "INFO";
	const LEVEL_DEBUG = "DEBUG";
	const LEVEL_NOTICE = "NOTICE";
	const LEVEL_ERROR = "ERROR";
	
	public function log($message, $level = self::LEVEL_INFO);
	public function setError();
	public function setInfo();
	public function setDebug();
	public function setNotice();
}