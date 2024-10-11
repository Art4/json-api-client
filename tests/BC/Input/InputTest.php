<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC\Input;

use Art4\JsonApiClient\Input\Input;
use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Input interface.
     */
    public function testBcForInputInterface(): void
    {
        /**
         * DO NOT CHANGE THIS CLASS!
         *
         * This anonymous class represents an implementation in user code
         */
        $class = new class implements Input {
            /**
             * Get the input as simple object
             *
             * This should be a native PH stdClass object, so Manager could
             * iterate over all public attributes
             *
             * @throws \Art4\JsonApiClient\Exception\InputException if something went wrong with the input
             *
             * @return \stdClass
             */
            public function getAsObject()
            {
                throw new \Exception('not implemented');
            }
        };

        $this->assertInstanceOf(Input::class, $class);
    }
}
