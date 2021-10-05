<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2021  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

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
