<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Helper\AccessKey;

/**
 * Accessable Interface
 */
interface Accessable
{
    /**
     * Get a value by a key
     *
     * @param int|string|AccessKey<string> $key The key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Check if a value exists
     *
     * @deprecated `\Art4\JsonApiClient\Accessable::has()` will add `bool` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @param int|string|AccessKey<string> $key The key
     *
     * @return bool
     */
    public function has($key);
    // public function has($key): bool;

    /**
     * Returns the keys of all setted values
     *
     * @deprecated `\Art4\JsonApiClient\Accessable::getKeys()` will add `array` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @return array<string|int> Keys of all setted values
     */
    public function getKeys();
    // public function getKeys(): array;
}
