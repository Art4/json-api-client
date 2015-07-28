<?php

//include_once(__DIR__.'/../src/autoload.php');

if ( ! @include_once __DIR__.'/../vendor/autoload.php')
{
	exit("You must set up the project dependencies, run the following commands:
> wget http://getcomposer.org/composer.phar
> php composer.phar install
");
}
