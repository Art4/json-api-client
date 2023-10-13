<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient;

/**
 * Factory Interface
 */
interface Factory
{
    /**
     * Create a new instance of a class
     *
     * @deprecated The `\Art4\JsonApiClient\Factory::make()` methods first parameter signature will be `string` in v2.0.
     * @deprecated `\Art4\JsonApiClient\Factory::make()` will add `\Art4\JsonApiClient\Accessable` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @param string $name
     * @param array<mixed|Manager|Accessable>  $args
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    public function make($name, array $args = []);
    // public function make(string $name, array $args = []): Accessable;
}
