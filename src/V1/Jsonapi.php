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
 * JSON API Object
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 */
final class Jsonapi extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException('Jsonapi has to be an object, "' . gettype($object) . '" given.');
        }

        if (property_exists($object, 'version')) {
            if (is_object($object->version) or is_array($object->version)) {
                throw new ValidationException('property "version" cannot be an object or array, "' . gettype($object->version) . '" given.');
            }

            $this->set('version', strval($object->version));
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this jsonapi object.');
        }
    }
}
