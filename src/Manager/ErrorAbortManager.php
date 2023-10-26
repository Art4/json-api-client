<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

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
            [$object, $this, new RootAccessable()]
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
     * @param mixed $default
     *
     * @return mixed
     */
    public function getParam(string $key, $default)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return $default;
    }
}
