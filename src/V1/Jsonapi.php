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

        foreach ($object as $key => $value) {
            if ($key === 'version') {
                if (is_object($value) or is_array($value)) {
                    throw new ValidationException('property "version" cannot be an object or array, "' . gettype($value) . '" given.');
                }

                $this->set('version', strval($value));
            } else if ($key === 'meta') {
                $this->set('meta', $this->create('Meta', $value));
            } else {
                $this->set($key, $value);
            }
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
