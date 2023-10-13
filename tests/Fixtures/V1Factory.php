<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Factory as FactoryInterface;

final class V1Factory implements FactoryInterface
{
    public $testcase;

    /**
     * Create a factory
     *
     * @param object $testcase
     * @param array  $args
     *
     * @return object
     */
    public function __construct($testcase)
    {
        return $this->testcase = $testcase;
    }

    /**
     * Create a new instance of a class
     *
     * @param string $name
     * @param array  $args
     *
     * @return object
     */
    public function make($name, array $args = [])
    {
        return $this->testcase->getMockBuilder(AccessableElement::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }
}
