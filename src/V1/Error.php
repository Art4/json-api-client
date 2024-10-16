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
 * Error Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class Error extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException(
                'Error has to be an object, "' . gettype($object) . '" given.'
            );
        }

        if (property_exists($object, 'id')) {
            if (!is_string($object->id)) {
                throw new ValidationException(
                    'property "id" has to be a string, "' .
                    gettype($object->id) . '" given.'
                );
            }

            $this->set('id', strval($object->id));
        }

        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('ErrorLink', $object->links));
        }

        if (property_exists($object, 'status')) {
            if (!is_string($object->status)) {
                throw new ValidationException(
                    'property "status" has to be a string, "' .
                    gettype($object->status) . '" given.'
                );
            }

            $this->set('status', strval($object->status));
        }

        if (property_exists($object, 'code')) {
            if (!is_string($object->code)) {
                throw new ValidationException(
                    'property "code" has to be a string, "' .
                    gettype($object->code) . '" given.'
                );
            }

            $this->set('code', strval($object->code));
        }

        if (property_exists($object, 'title')) {
            if (!is_string($object->title)) {
                throw new ValidationException(
                    'property "title" has to be a string, "' .
                    gettype($object->title) . '" given.'
                );
            }

            $this->set('title', strval($object->title));
        }

        if (property_exists($object, 'detail')) {
            if (!is_string($object->detail)) {
                throw new ValidationException(
                    'property "detail" has to be a string, "' .
                    gettype($object->detail) . '" given.'
                );
            }

            $this->set('detail', strval($object->detail));
        }

        if (property_exists($object, 'source')) {
            $this->set('source', $this->create('ErrorSource', $object->source));
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
        }
    }

    /**
     * Get a value by the key of this object
     *
     * @param int|string|AccessKey<string> $key The key of the value
     */
    public function get($key): mixed
    {
        try {
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException(
                '"' . $key . '" doesn\'t exist in this error object.'
            );
        }
    }
}
