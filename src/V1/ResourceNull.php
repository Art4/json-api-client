<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Helper\AccessKey;

/**
 * Null Resource
 */
final class ResourceNull implements Accessable, Element
{
    /**
     * Sets the manager and parent
     *
     * @param mixed $data The data for this Element
     */
    public function __construct(mixed $data, Manager $manager, Accessable $parent) {}

    /**
     * Check if a value exists in this resource
     *
     * @param int|string|AccessKey<string> $key The key of the value
     *
     * @return bool false
     */
    public function has($key): bool
    {
        if (!is_int($key) && !is_string($key) && (!is_object($key) || !$key instanceof AccessKey)) {
            trigger_error(sprintf(
                '%s::has(): Providing Argument #1 ($key) as `%s` is deprecated since 1.2.0, please provide as `int|string` instead.',
                get_class($this),
                gettype($key)
            ), \E_USER_DEPRECATED);
        }

        return false;
    }

    /**
     * Returns the keys of all setted values in this resource
     *
     * @return array<string> Keys of all setted values
     */
    public function getKeys(): array
    {
        return [];
    }

    /**
     * Get a value by the key of this identifier
     *
     * @param int|string|AccessKey<string> $key The key of the value
     */
    public function get($key): void
    {
        throw new AccessException('A ResourceNull has no values.');
    }
}
