<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Element interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForElementInterface(): void
    {
        $data = null;
        $manager = $this->createMock(Manager::class);
        $parent = $this->createMock(Accessable::class);

        $class = new class ($data, $manager, $parent) implements Element {
            /**
             * Sets the manager and parent
             *
             * @param mixed $data The data for this Element
             */
            public function __construct($data, Manager $manager, Accessable $parent) {}
        };

        $this->assertInstanceOf(Element::class, $class);
    }
}
