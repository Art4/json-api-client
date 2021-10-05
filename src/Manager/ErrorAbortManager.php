<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Manager;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Helper\RootAccessable;
use Art4\JsonApiClient\Input\Input;
use Art4\JsonApiClient\Input\RequestInput;
use Art4\JsonApiClient\Manager;

/**
 * A Manager that aborts if a validation error occurs
 */
final class ErrorAbortManager implements Manager
{
    private Factory $factory;

    /** @var array<string, mixed> */
    private array $config = [];

    /** @var array<string, mixed> */
    private array $default = [
        'optional_item_id' => false,
    ];

    /**
     * Create a Manager
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Parse the input
     *
     * @throws \Art4\JsonApiClient\Exception\InputException If $input contains invalid JSON API
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
     */
    public function parse(Input $input): Accessable
    {
        // fill config
        $this->config = $this->default;

        if ($input instanceof RequestInput) {
            $this->config['optional_item_id'] = true;
        }

        $object = $input->getAsObject();

        $document = $this->getFactory()->make(
            'Document',
            [$object, $this, new RootAccessable]
        );

        // Clear config
        $this->config = [];

        return $document;
    }

    /**
     * Get a factory from the manager
     */
    public function getFactory(): Factory
    {
        return $this->factory;
    }

    /**
     * Get a param by key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($key, $default)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return $default;
    }
}
