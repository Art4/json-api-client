<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
final class ResourceIdentifier extends AbstractElement
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
            throw new ValidationException('Resource has to be an object, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'type')) {
            throw new ValidationException('A resource object MUST contain a type');
        }

        if (! is_string($object->type)) {
            throw new ValidationException('A resource type MUST be a string');
        }

        if (! property_exists($object, 'id')) {
            throw new ValidationException('A resource object MUST contain an id');
        }

        if (! is_string($object->id)) {
            throw new ValidationException('A resource id MUST be a string');
        }

        $this->set('type', $object->type);
        $this->set('id', $object->id);

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this identifier.');
        }
    }
}
