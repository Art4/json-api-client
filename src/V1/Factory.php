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

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\FactoryException;
use Art4\JsonApiClient\Factory as FactoryInterface;

/**
 * Factory for V1 Elements
 */
final class Factory implements FactoryInterface
{
    /**
     * @var array
     */
    private $classes = [
        'Attributes'                   => 'Art4\JsonApiClient\V1\Attributes',
        'Document'                     => 'Art4\JsonApiClient\V1\Document',
        'DocumentLink'                 => 'Art4\JsonApiClient\V1\DocumentLink',
        'Error'                        => 'Art4\JsonApiClient\V1\Error',
        'ErrorCollection'              => 'Art4\JsonApiClient\V1\ErrorCollection',
        'ErrorLink'                    => 'Art4\JsonApiClient\V1\ErrorLink',
        'ErrorSource'                  => 'Art4\JsonApiClient\V1\ErrorSource',
        'Jsonapi'                      => 'Art4\JsonApiClient\V1\Jsonapi',
        'Link'                         => 'Art4\JsonApiClient\V1\Link',
        'Meta'                         => 'Art4\JsonApiClient\V1\Meta',
        'Relationship'                 => 'Art4\JsonApiClient\V1\Relationship',
        'RelationshipCollection'       => 'Art4\JsonApiClient\V1\RelationshipCollection',
        'RelationshipLink'             => 'Art4\JsonApiClient\V1\RelationshipLink',
        'ResourceCollection'           => 'Art4\JsonApiClient\V1\ResourceCollection',
        'ResourceIdentifier'           => 'Art4\JsonApiClient\V1\ResourceIdentifier',
        'ResourceIdentifierCollection' => 'Art4\JsonApiClient\V1\ResourceIdentifierCollection',
        'ResourceItem'                 => 'Art4\JsonApiClient\V1\ResourceItem',
        'ResourceItemLink'             => 'Art4\JsonApiClient\V1\ResourceItemLink',
        'ResourceNull'                 => 'Art4\JsonApiClient\V1\ResourceNull',
    ];

    /**
     * @param array $overload specs to be overloaded with custom classes
     */
    public function __construct(array $overload = [])
    {
        if ($overload) {
            $this->classes = array_replace($this->classes, $overload);
        }
    }

    /**
     * Create a new instance of a class
     *
     * @param string $name
     * @param array  $args
     *
     * @return Art4\JsonApiClient\Accessable
     */
    public function make($name, array $args = [])
    {
        if (! isset($this->classes[$name])) {
            throw new FactoryException('"' . $name . '" is not a registered class');
        }

        $class = new \ReflectionClass($this->classes[$name]);

        return $class->newInstanceArgs($args);
    }
}
