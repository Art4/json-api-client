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
 * Attributes Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-attributes
 */
final class Attributes extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException('Attributes has to be an object, "' . gettype($object) . '" given.');
        }

        if (property_exists($object, 'type') or property_exists($object, 'id') or property_exists($object, 'relationships') or property_exists($object, 'links')) {
            throw new ValidationException('These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`');
        }

        foreach (get_object_vars($object) as $name => $value) {
            $this->set($name, $value);
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }
}
