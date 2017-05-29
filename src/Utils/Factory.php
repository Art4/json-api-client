<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Exception\FactoryException;

final class Factory implements FactoryInterface
{
	/**
	 * @var array
	 */
	protected $classes = [
		'Attributes'                    => 'Art4\JsonApiClient\Attributes',
		'Document'                      => 'Art4\JsonApiClient\Document',
		'DocumentLink'                  => 'Art4\JsonApiClient\DocumentLink',
		'Error'                         => 'Art4\JsonApiClient\Error',
		'ErrorCollection'               => 'Art4\JsonApiClient\ErrorCollection',
		'ErrorLink'                     => 'Art4\JsonApiClient\ErrorLink',
		'ErrorSource'                   => 'Art4\JsonApiClient\ErrorSource',
		'Jsonapi'                       => 'Art4\JsonApiClient\Jsonapi',
		'Link'                          => 'Art4\JsonApiClient\Link',
		'Meta'                          => 'Art4\JsonApiClient\Meta',
		'Relationship'                  => 'Art4\JsonApiClient\Relationship',
		'RelationshipCollection'        => 'Art4\JsonApiClient\RelationshipCollection',
		'RelationshipLink'              => 'Art4\JsonApiClient\RelationshipLink',
		'ResourceCollection'            => 'Art4\JsonApiClient\ResourceCollection',
		'ResourceIdentifier'            => 'Art4\JsonApiClient\ResourceIdentifier',
		'ResourceIdentifierCollection'  => 'Art4\JsonApiClient\ResourceIdentifierCollection',
		'ResourceItem'                  => 'Art4\JsonApiClient\ResourceItem',
		'ResourceItemLink'              => 'Art4\JsonApiClient\ResourceItemLink',
		'ResourceNull'                  => 'Art4\JsonApiClient\ResourceNull',
	];

	/**
	 * @param array $overload Specs to be overloaded with custom classes.
	 */
	public function __construct(array $overload = [])
	{
		if ($overload)
		{
			$this->classes = array_replace($this->classes, $overload);
		}
	}

	/**
	 * Create a new instance of a class
	 *
	 * @param  string $name
	 * @param  array  $args
	 * @return object
	 */
	public function make($name, array $args = [])
	{
		if ( ! isset($this->classes[$name]) )
		{
			throw new FactoryException('"' . $name . '" is not a registered class');
		}

		$class = new \ReflectionClass($this->classes[$name]);

		return $class->newInstanceArgs($args);
	}
}
