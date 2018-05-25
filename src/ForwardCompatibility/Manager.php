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

namespace Art4\JsonApiClient\ForwardCompatibility;

@trigger_error(__NAMESPACE__ . '\Manager is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager\ErrorAbortManager instead', E_USER_DEPRECATED);

use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Input\Input;
use Art4\JsonApiClient\Manager as ManagerInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Utils\FactoryInterface;

/**
 * Manager for Forward Compatibility
 *
 * @deprecated Manager is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager\ErrorAbortManager instead
 */
final class Manager implements ManagerInterface, FactoryManagerInterface
{
    private $factory;

    private $config = [
        'optional_item_id' => false,
    ];

    /**
     * Create a Manager
     *
     * @param Art4\JsonApiClient\Factory $factory
     * @param mixed                      $params  A config array
     *
     * @return object
     */
    public function __construct(Factory $factory, $params)
    {
        $this->factory = $factory;
        $this->config = $params;
    }

    /**
     * Parse the input
     *
     * @param Art4\JsonApiClient\Input\Input $input
     *
     * @throws Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
     *
     * @return Art4\JsonApiClient\Accessable
     */
    public function parse(Input $input)
    {
        throw new ValidationException(sprintf(
            '"%s" is not implemented.',
            __METHOD__
        ));
    }

    /**
     * Get a factory from the manager
     *
     * @return Art4\JsonApiClient\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get a param by key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @throws \InvalidArgumentException If $key is not a valid config key and no default was set
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

    /**
     * Set a factory into the manager
     *
     * @param FactoryInterface $factory
     *
     * @return object
     */
    public function setFactory(FactoryInterface $factory)
    {
        throw new \Exception(sprintf(
            '"%s" is not implemented.',
            __METHOD__
        ));
    }

    /**
     * Get a config by key
     *
     * @param string $key
     *
     * @throws \InvalidArgumentException If $key is not a valid config key
     *
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->getParam($key, null);
    }

    /**
     * Set a config
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException If $key is not a valid config key
     *
     * @return self
     */
    public function setConfig($key, $value)
    {
        throw new \Exception(sprintf(
            '"%s" is not implemented.',
            __METHOD__
        ));
    }
}
