<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Helper;

use SplStack;

/**
 * AccessKey
 *
 * @extends SplStack<string>
 *
 * @internal
 */
final class AccessKey extends SplStack
{
    /**
     * Transforms the Key to a string
     *
     * @param int|string $key
     *
     * @return AccessKey<string>
     */
    public static function create($key): AccessKey
    {
        // Ignore arrays and objects
        if (is_object($key) or is_array($key)) {
            $key = '';
        }

        $key_string = strval($key);

        $key = new self();
        $key->raw = $key_string;

        $keys = explode('.', $key_string);

        foreach ($keys as $value) {
            $key->push($value);
        }

        $key->rewind();

        return $key;
    }

    /**
     * @var string Raw key
     */
    public string $raw = '';

    /**
     * Transforms the Key to a string
     */
    public function __toString(): string
    {
        return $this->raw;
    }
}
