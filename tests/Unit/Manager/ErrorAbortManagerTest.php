<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Manager;

use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use PHPUnit\Framework\TestCase;

class ErrorAbortManagerTest extends TestCase
{
    /**
     * @test
     */
    public function testCreateWithConstructorReturnsSelf(): void
    {
        $factory = $this->createMock(Factory::class);
        $manager = new ErrorAbortManager($factory);

        $this->assertSame($factory, $manager->getFactory());
    }

    /**
     * @test
     */
    public function testGetParamReturnsDefault(): void
    {
        $factory = $this->createMock(Factory::class);
        $manager = new ErrorAbortManager($factory);

        $this->assertSame('default', $manager->getParam('not-existing-param', 'default'));
    }
}
