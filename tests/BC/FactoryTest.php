<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Factory interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForFactoryInterface(): void
    {
        $class = new class () implements Factory {
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
