<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Link Object
 *
 * @see http://jsonapi.org/format/#error-objects
 *
 * An error object MAY have the following members:
 * - links: a links object containing the following members:
 *   - about: a link that leads to further details about this particular occurrence of the problem.
 */
final class ErrorLink extends AbstractElement
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
            throw new ValidationException('Link has to be an object, "' . gettype($object) . '" given.');
        }

        $links = get_object_vars($object);

        if (! array_key_exists('about', $links)) {
            throw new ValidationException('ErrorLink MUST contain these properties: about');
        }

        if (! is_string($links['about']) and ! is_object($links['about'])) {
            throw new ValidationException('Link has to be an object or string, "' . gettype($links['about']) . '" given.');
        }

        if (is_string($links['about'])) {
            $this->set('about', strval($links['about']));
        } else {
            $this->set('about', $this->create('Link', $links['about']));
        }

        unset($links['about']);

        // custom links
        foreach ($links as $name => $value) {
            $this->setLink($name, $value);
        }
    }

    /**
     * Get a value by the key of this object
     *
     * @param string $key The key of the value
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
     * @param string              $name The name of the link
     * @param string|object|mixed $link The link
     */
    private function setLink(string $name, $link): void
    {
        if (! is_string($link) and ! is_object($link)) {
            throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
        }

        if (is_string($link)) {
            $this->set($name, strval($link));

            return;
        }

        // Now $link can only be an object
        $this->set($name, $this->create('Link', $link));
    }
}
