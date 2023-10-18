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
final class ResourceItem extends AbstractElement
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

        $this->set('type', $object->type);

        if (
            $this->getManager()->getParam('optional_item_id', false) === false
            or ! $this->getParent()->has('data') // If parent has no `data` than parent is a ResourceCollection
        ) {
            if (! property_exists($object, 'id')) {
                throw new ValidationException('A resource object MUST contain an id');
            }

            if (! is_string($object->id)) {
                throw new ValidationException('A resource id MUST be a string');
            }

            $this->set('id', $object->id);
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
        }

        if (property_exists($object, 'attributes')) {
            $this->set('attributes', $this->create('Attributes', $object->attributes));
        }

        if (property_exists($object, 'relationships')) {
            $this->set('relationships', $this->create('RelationshipCollection', $object->relationships));
        }

        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('ResourceItemLink', $object->links));
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this resource.');
        }
    }
}
