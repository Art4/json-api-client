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
 * Link Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
final class Link extends AbstractElement
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
            throw new ValidationException('Link has to be an object or string, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'href')) {
            throw new ValidationException('Link must have a "href" attribute.');
        }

        foreach (get_object_vars($object) as $name => $value) {
            $this->setAsLink($name, $value);
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }

    /**
     * Set a link
     *
     * @param string        $name The Name
     * @param string|object $link The Link
     */
    private function setAsLink(string $name, $link): void
    {
        if ($name === 'meta') {
            $this->set($name, $this->create('Meta', $link));

            return;
        }

        // every link must be an URL
        if (! is_string($link)) {
            throw new ValidationException('Every link attribute has to be a string, "' . gettype($link) . '" given.');
        }

        $this->set($name, strval($link));
    }
}
