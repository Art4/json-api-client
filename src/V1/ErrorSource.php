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
 * Error Source Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorSource extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException('ErrorSource has to be an object, "' . gettype($object) . '" given.');
        }

        foreach (get_object_vars($object) as $key => $value) {
            if ($key === 'pointer') {
                if (!is_string($value)) {
                    throw new ValidationException('property "pointer" has to be a string, "' . gettype($value) . '" given.');
                }

                $this->set('pointer', strval($value));
            } elseif ($key === 'parameter') {
                if (!is_string($value)) {
                    throw new ValidationException('property "parameter" has to be a string, "' . gettype($value) . '" given.');
                }

                $this->set('parameter', strval($value));
            } else {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Get a value by the key of this document
     *
     * @param int|string|AccessKey<string> $key The key of the value
     */
    public function get($key): mixed
    {
        try {
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in this error source.');
        }
    }
}
