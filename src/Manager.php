<?php

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
     * @deprecated `\Art4\JsonApiClient\Manager::parse()` will add `\Art4\JsonApiClient\Accessable` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @throws \Art4\JsonApiClient\Exception\InputException If $input contains invalid JSON API
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    public function parse(Input $input);
    // public function parse(Input $input): Accessable;

    /**
     * Get a factory from the manager
     *
     * @deprecated `\Art4\JsonApiClient\Manager::getFactory()` will add `\Art4\JsonApiClient\Factory` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @return \Art4\JsonApiClient\Factory
     */
    public function getFactory();
    // public function getFactory(): Factory;

    /**
     * Get a param by key
     *
     * @deprecated The `\Art4\JsonApiClient\Manager::getParam()` methods first parameter signature will be `string` in v2.0.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($key, $default);
    // public function getParam(string $key, $default);
}
