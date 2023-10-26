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
     * @return AccessKey<string>
     */
    public static function create(string $key): AccessKey
    {
        $accessKey = new self();
        $accessKey->raw = $key;

        foreach (explode('.', $key) as $value) {
            $accessKey->push($value);
        }

        $accessKey->rewind();

        return $accessKey;
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
