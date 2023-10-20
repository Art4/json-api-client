<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Helper\AccessKey;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
final class Relationship extends AbstractElement
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
            throw new ValidationException('Relationship has to be an object, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'links') and ! property_exists($object, 'data') and ! property_exists($object, 'meta')) {
            throw new ValidationException('A Relationship object MUST contain at least one of the following properties: links, data, meta');
        }

        if (property_exists($object, 'data')) {
            if ($object->data === null) {
                $this->set('data', $object->data);
            } elseif (is_array($object->data)) {
                $this->set('data', $this->create('ResourceIdentifierCollection', $object->data));
            } else {
                $this->set('data', $this->create('ResourceIdentifier', $object->data));
            }
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
        }

        // Parse 'links' after 'data'
        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('RelationshipLink', $object->links));
        }
    }

    /**
     * Get a value by the key of this object
     *
     * @param int|string|AccessKey<string> $key The key of the value
     *
     * @return mixed The value
     */
    public function get($key)
    {
        try {
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in Relationship.');
        }
    }
}
