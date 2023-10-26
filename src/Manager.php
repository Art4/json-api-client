<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Input\Input;

/**
 * Manager Interface
 */
interface Manager
{
    /**
     * Parse the input
     *
     * @return-type-will-change Accessable `\Art4\JsonApiClient\Manager::parse()` will add `\Art4\JsonApiClient\Accessable` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @throws \Art4\JsonApiClient\Exception\InputException If $input contains invalid JSON API
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    public function parse(Input $input)/*: Accessable */;

    /**
     * Get a factory from the manager
     *
     * @return-type-will-change Factory `\Art4\JsonApiClient\Manager::getFactory()` will add `\Art4\JsonApiClient\Factory` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @return \Art4\JsonApiClient\Factory
     */
    public function getFactory()/*: Factory */;

    /**
     * Get a param by key
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function getParam(string $key, $default);
}
