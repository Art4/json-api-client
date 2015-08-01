<?php


spl_autoload_register(function ($class)
{
	$class = str_replace('\\', '/', $class);
	$class = str_replace('Art4/JsonApiClient/Tests/', '', $class);

	$path = dirname(__FILE__).'/'.$class.'.php';

	if ( file_exists($path) )
	{
		require_once $path;
	}
});

if ( ! @include_once __DIR__.'/../vendor/autoload.php')
{
	exit("You must set up the project dependencies, run the following commands:
> wget http://getcomposer.org/composer.phar
> php composer.phar install
");
}
