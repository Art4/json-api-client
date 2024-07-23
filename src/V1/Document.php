<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Helper\AccessKey;

/**
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
final class Document extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException('Document has to be an object, "' . gettype($object) . '" given.');
        }

        if (!property_exists($object, 'data') and !property_exists($object, 'meta') and !property_exists($object, 'errors')) {
            throw new ValidationException('Document MUST contain at least one of the following properties: data, errors, meta');
        }

        if (property_exists($object, 'data') and property_exists($object, 'errors')) {
            throw new ValidationException('The properties `data` and `errors` MUST NOT coexist in Document.');
        }

        if (property_exists($object, 'included') and !property_exists($object, 'data')) {
            throw new ValidationException('If Document does not contain a `data` property, the `included` property MUST NOT be present either.');
        }

        foreach (get_object_vars($object) as $key => $value) {
            $value = match ($key) {
                'data' => $this->parseData($value),
                'meta' => $this->create('Meta', $value),
                'errors' => $this->create('ErrorCollection', $value),
                'included' => $this->create('ResourceCollection', $value),
                'jsonapi' => $this->create('Jsonapi', $value),
                'links' => $this->create('DocumentLink', $value),
                default => $value,
            };

            $this->set($key, $value);
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
            throw new AccessException('"' . $key . '" doesn\'t exist in Document.');
        }
    }

    /**
     * Parse the data value
     *
     * @throws ValidationException If $data isn't null or an object
     */
    private function parseData(mixed $data): Accessable
    {
        if ($data === null) {
            return $this->create('ResourceNull', $data);
        }

        if (is_array($data)) {
            return $this->create('ResourceCollection', $data);
        }

        if (!is_object($data)) {
            throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
        }

        $object_keys = array_keys(get_object_vars($data));
        sort($object_keys);

        // the properties must be type and id or
        // the 3 properties must be type, id and meta
        if ($object_keys === ['id', 'type'] or $object_keys === ['id', 'meta', 'type']) {
            $resource = $this->create('ResourceIdentifier', $data);
        } else {
            // #Workaround: preset `data` with null, so ResourceItem can distinguish his parent between Document and ResourceCollection
            $this->set('data', null);
            $resource = $this->create('ResourceItem', $data);
        }

        return $resource;
    }
}
