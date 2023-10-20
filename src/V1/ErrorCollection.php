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
 * Error Collection Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorCollection extends AbstractElement
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
        if (! is_array($object)) {
            throw new ValidationException('Errors for a collection has to be in an array, "' . gettype($object) . '" given.');
        }

        if (count($object) === 0) {
            throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
        }

        foreach ($object as $err) {
            $this->set('', $this->create('Error', $err));
        }
    }

    /**
     * Get a value by the key of this document
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this collection.');
        }
    }
}
