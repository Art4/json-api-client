<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Accessable;
use PHPUnit\Framework\TestCase;

class AccessableTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Accessable interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForAccessableInterface()
    {
        $class = new class () implements Accessable {
            /**
             * Get a value by a key
             *
             * @param mixed $key The key
             *
             * @return mixed
             */
            public function get($key)
            {
                throw new \Exception('not implemented');
            }

            /**
             * Check if a value exists
             *
             * @param mixed $key The key
             *
             * @return bool
             */
            public function has($key)
            {
                throw new \Exception('not implemented');
            }

            /**
             * Returns the keys of all setted values
             *
             * @return array<string> Keys of all setted values
             */
            public function getKeys()
            {
                throw new \Exception('not implemented');
            }
        };

        $this->assertInstanceOf(Accessable::class, $class);
    }
}
