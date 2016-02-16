<?php
function includeIfExists($file) {
	if (file_exists($file))
		return include $file;
}

if ( ( !$loader = includeIfExists(__DIR__.'/../vendor/autoload.php') ) ) 
	die('Class loader error.');

$loader->add('RBTests', __DIR__);

return $loader;
