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

namespace Art4\JsonApiClient\Utils;

@trigger_error(__NAMESPACE__ . '\FactoryManagerInterface is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager instead', E_USER_DEPRECATED);

/**
 * Manager Interface
 *
 * @deprecated FactoryManagerInterface is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager instead
 */
interface FactoryManagerInterface
{
    /**
     * Set a factory into the manager
     *
     * @param FactoryInterface $factory
     *
     * @return object
     */
    public function setFactory(FactoryInterface $factory);

    /**
     * Get a factory from the manager
     *
     * @return FactoryInterface
     */
    public function getFactory();

    /**
     * Get a config by key
     *
     * @param string $key
     *
     * @throws \InvalidArgumentException If $key is not a valid config key
     *
     * @return mixed
     */
    public function getConfig($key);

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
    public function setConfig($key, $value);
}
