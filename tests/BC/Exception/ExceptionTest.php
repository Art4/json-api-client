<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC\Exception;

use Art4\JsonApiClient\Exception\Exception;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Exception interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForExceptionInterface(): void
    {
        $class = new class () implements Exception {};

        $this->assertInstanceOf(Exception::class, $class);
    }
}
