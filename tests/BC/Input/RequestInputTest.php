<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC\Input;

use Art4\JsonApiClient\Input\RequestInput;
use PHPUnit\Framework\TestCase;

class RequestInputTest extends TestCase
{
    /**
     * This test will test a custom implementation of the RequestInput interface.
     */
    public function testBcForRequestInputInterface(): void
    {
        /**
         * DO NOT CHANGE THIS CLASS!
         *
         * This anonymous class represents an implementation in user code
         */
        $class = new class implements RequestInput {};

        $this->assertInstanceOf(RequestInput::class, $class);
    }
}
