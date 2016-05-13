<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
