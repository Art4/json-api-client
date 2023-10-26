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
     * @return-type-will-change Accessable `\Art4\JsonApiClient\Factory::make()` will add `\Art4\JsonApiClient\Accessable` as a native return type declaration in 2.0.0, do the same in your implementation now to avoid errors.
     *
     * @param array<mixed|Manager|Accessable> $args
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    public function make(string $name, array $args = [])/*: Accessable */;
}
