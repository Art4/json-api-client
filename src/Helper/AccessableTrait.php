<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Helper;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;

/**
 * Trait for Accessables
 *
 * @internal
 */
trait AccessableTrait
{
    /**
     * @var array<mixed>
     */
    private array $data = [];

    /**
     * Set a value
     *
     * @param mixed  $value The Value
     */
    final protected function set(string $key, $value): void
    {
        // Allow non-associative array for collections
        if ($key === '') {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * Returns the keys of all setted values
     *
     * @return array<int|string> Keys of all setted values
     */
    final public function getKeys(): array
    {
        return array_keys($this->data);
    }

    /**
     * Check if a value exists
     *
     * @param int|string|AccessKey<string> $key The key
     */
    final public function has($key): bool
    {
        if (!is_int($key) && !is_string($key) && (!is_object($key) || !$key instanceof AccessKey)) {
            trigger_error(sprintf(
                '%s::has(): Providing Argument #1 ($key) as `%s` is deprecated since 1.2.0, please provide as `int|string` instead.',
                get_class($this),
                gettype($key)
            ), \E_USER_DEPRECATED);

            $key = '';
        }

        $key = $this->parseKey($key);

        $string = $key->shift();
        $key->next();

        if ($key->count() === 0) {
            return array_key_exists($string, $this->data);
        }

        if (!array_key_exists($string, $this->data)) {
            return false;
        }

        $value = $this->getValue($string);

        // #TODO Handle other objects and arrays
        if (!$value instanceof Accessable) {
            // throw new AccessException('The existance for the key "' . $key->raw . '" could\'nt be checked.');
            return false;
        }

        return $value->has($key);
    }

    /**
     * Get a value by a key
     *
     * @param int|string|AccessKey<string> $key The key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!is_int($key) && !is_string($key) && (!is_object($key) || !$key instanceof AccessKey)) {
            trigger_error(sprintf(
                '%s::get(): Providing Argument #1 ($key) as `%s` is deprecated since 1.2.0, please provide as `int|string` instead.',
                get_class($this),
                gettype($key)
            ), \E_USER_DEPRECATED);

            $key = '';
        }

        $key = $this->parseKey($key);

        $string = $key->shift();
        $key->next();

        $value = $this->getValue($string);

        if ($key->count() === 0) {
            return $value;
        }

        // #TODO Handle other objects and arrays
        if (!$value instanceof Accessable) {
            throw new AccessException('Could not get the value for the key "' . $key->raw . '".');
        }

        return $value->get($key);
    }

    /**
     * Get a value by the key
     *
     * @throws AccessException
     *
     * @return mixed The value
     */
    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        throw new AccessException('Could not get the value for the key "' . $key . '".');
    }

    /**
     * Parse a dot.notated.key to an object
     *
     * @param int|string|AccessKey<string> $key The key
     */
    private function parseKey($key): AccessKey
    {
        if (is_object($key) and $key instanceof AccessKey) {
            return $key;
        }

        return AccessKey::create(strval($key));
    }
}
