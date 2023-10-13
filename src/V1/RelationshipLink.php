<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\AbstractElement;

/**
 * Relationship Link Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * links: a links object containing at least one of the following:
 * - self: a link for the relationship itself (a "relationship link"). This link allows
 *   the client to directly manipulate the relationship. For example, it would allow a
 *   client to remove an author from an article without deleting the people resource itself.
 * - related: a related resource link
 *
 * A relationship object that represents a to-many relationship MAY also contain pagination
 * links under the links member, as described below.
 */
final class RelationshipLink extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @param mixed $object The data
     *
     * @throws ValidationException
     */
    protected function parse($object): void
    {
        if (! is_object($object)) {
            throw new ValidationException('RelationshipLink has to be an object, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'self') and ! property_exists($object, 'related')) {
            throw new ValidationException('RelationshipLink has to be at least a "self" or "related" link');
        }

        $links = get_object_vars($object);

        if (array_key_exists('self', $links)) {
            if (! is_string($links['self']) and ! is_object($links['self'])) {
                throw new ValidationException('property "self" has to be a string or object, "' . gettype($links['self']) . '" given.');
            }

            $this->setLink('self', $links['self']);

            unset($links['self']);
        }

        if (array_key_exists('related', $links)) {
            if (! is_string($links['related']) and ! is_object($links['related'])) {
                throw new ValidationException('property "related" has to be a string or object, "' . gettype($links['related']) . '" given.');
            }

            $this->setLink('related', $links['related']);

            unset($links['related']);
        }

        // Pagination links
        if ($this->getParent()->has('data') and
            $this->getParent()->get('data') instanceof ResourceIdentifierCollection
        ) {
            if (array_key_exists('first', $links)) {
                $this->setPaginationLink('first', $links['first']);

                unset($links['first']);
            }

            if (array_key_exists('last', $links)) {
                $this->setPaginationLink('last', $links['last']);

                unset($links['last']);
            }

            if (array_key_exists('prev', $links)) {
                $this->setPaginationLink('prev', $links['prev']);

                unset($links['prev']);
            }

            if (array_key_exists('next', $links)) {
                $this->setPaginationLink('next', $links['next']);

                unset($links['next']);
            }
        }

        // custom links
        foreach ($links as $name => $value) {
            $this->setLink($name, $value);
        }
    }

    /**
     * Get a value by the key of this object
     *
     * @param string $key The key of the value
     *
     * @return mixed The value
     */
    public function get($key)
    {
        try {
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }

    /**
     * Set a pagination link
     *
     * @param string            $name  The name of the link
     * @param string|null|mixed $value The link
     */
    private function setPaginationLink(string $name, $value): void
    {
        if (! is_string($value) and ! is_null($value)) {
            throw new ValidationException('property "' . $name . '" has to be a string or null, "' . gettype($value) . '" given.');
        }

        // null is ignored
        if (! is_null($value)) {
            $this->set($name, strval($value));
        }
    }

    /**
     * Set a link
     *
     * @param string              $name The name of the link
     * @param string|object|mixed $link The link
     */
    private function setLink(string $name, $link): void
    {
        if (! is_string($link) and ! is_object($link)) {
            throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
        }

        if (is_string($link)) {
            $this->set($name, strval($link));
        } else {
            // Now $link can only be an object
            $this->set($name, $this->create('Link', $link));
        }
    }
}
