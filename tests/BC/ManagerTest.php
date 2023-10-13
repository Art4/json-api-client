<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Input\Input;
use Art4\JsonApiClient\Manager;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Manager interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForManagerInterface()
    {
        $class = new class () implements Manager {
            /**
             * Parse the input
             *
             * @param \Art4\JsonApiClient\Input\Input $input
             *
             * @throws \Art4\JsonApiClient\Exception\InputException If $input contains invalid JSON API
             * @throws \Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
             *
             * @return \Art4\JsonApiClient\Accessable
             */
            public function parse(Input $input)
            {
                throw new \Exception('not implemented');
            }

            /**
             * Get a factory from the manager
             *
             * @return \Art4\JsonApiClient\Factory
             */
            public function getFactory()
            {
                throw new \Exception('not implemented');
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
                throw new \Exception('not implemented');
            }
        };

        $this->assertInstanceOf(Manager::class, $class);
    }
}
