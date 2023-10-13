<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

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
     * @var array<string, class-string>
     */
    private array $classes = [
        'Attributes'                   => Attributes::class,
        'Document'                     => Document::class,
        'DocumentLink'                 => DocumentLink::class,
        'Error'                        => Error::class,
        'ErrorCollection'              => ErrorCollection::class,
        'ErrorLink'                    => ErrorLink::class,
        'ErrorSource'                  => ErrorSource::class,
        'Jsonapi'                      => Jsonapi::class,
        'Link'                         => Link::class,
        'Meta'                         => Meta::class,
        'Relationship'                 => Relationship::class,
        'RelationshipCollection'       => RelationshipCollection::class,
        'RelationshipLink'             => RelationshipLink::class,
        'ResourceCollection'           => ResourceCollection::class,
        'ResourceIdentifier'           => ResourceIdentifier::class,
        'ResourceIdentifierCollection' => ResourceIdentifierCollection::class,
        'ResourceItem'                 => ResourceItem::class,
        'ResourceItemLink'             => ResourceItemLink::class,
        'ResourceNull'                 => ResourceNull::class,
    ];

    /**
     * @param array<string, class-string> $overload specs to be overloaded with custom classes
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
     * @param string        $name
     * @param array<mixed>  $args
     */
    public function make($name, array $args = []): Accessable
    {
        if (! isset($this->classes[$name])) {
            throw new FactoryException('"' . $name . '" is not a registered class');
        }

        $class = new \ReflectionClass($this->classes[$name]);

        $object = $class->newInstanceArgs($args);

        if (! $object instanceof Accessable) {
            throw new FactoryException(sprintf(
                '%s must be instance of `%s`',
                $this->classes[$name],
                Accessable::class
            ));
        }

        return $object;
    }
}
