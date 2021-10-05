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

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

class FactoryTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Factory interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForFactoryInterface()
    {
        $class = new class() implements Factory {
            /**
             * Create a new instance of a class
             *
             * @param string $name
             * @param array<mixed|Manager|Accessable>  $args
             *
             * @return \Art4\JsonApiClient\Accessable
             */
            public function make($name, array $args = [])
            {
                throw new \Exception('not implemented');
            }
        };

        $this->assertInstanceOf(Factory::class, $class);
    }
}
