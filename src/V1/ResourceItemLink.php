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
 * ItemLink Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
final class ResourceItemLink extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @throws ValidationException
     */
    protected function parse(mixed $object): void
    {
        if (!is_object($object)) {
            throw new ValidationException('ItemLink has to be an object, "' . gettype($object) . '" given.');
        }

        foreach (get_object_vars($object) as $name => $value) {
            $this->setLink($name, $value);
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

    /**
     * Set a link
     *
     * @param string|object|mixed $link The Link
     */
    private function setLink(string $name, $link): void
    {
        // from spec: aA link MUST be represented as either:
        // - a string containing the link's URL.
        // - an object ("link object") which can contain the following members:
        if (!is_object($link) and !is_string($link)) {
            throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
        }

        if (is_string($link)) {
            parent::set($name, strval($link));

            return;
        }

        // Now $link can only be an object
        $this->set($name, $this->create('Link', $link));
    }
}
