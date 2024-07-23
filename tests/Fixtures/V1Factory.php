<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Factory as FactoryInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

final class V1Factory implements FactoryInterface
{
    public TestCase $testcase;

    /**
     * Create a factory
     */
    public function __construct(TestCase $testcase)
    {
        $this->testcase = $testcase;
    }

    /**
     * Create a new instance of a class
     *
     * @param string $name
     * @param array<mixed> $args
     *
     * @return object
     */
    public function make($name, array $args = [])
    {
        return (new MockBuilder($this->testcase, AccessableElement::class))
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock()
        ;
    }
}
