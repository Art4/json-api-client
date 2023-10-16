<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\ResourceNull;
use PHPUnit\Framework\TestCase;

class ResourceNullTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp(): void
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * @param mixed $input
     */
    public function testCreateWithDataProvider($input): void
    {
        $resource = new ResourceNull(
            $input,
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(Accessable::class, $resource);

        $this->assertFalse($resource->has('something'));
        $this->assertSame([], $resource->getKeys());
    }

    /**
     * @test get throws Exception
     */
    public function testGetThrowsException(): void
    {
        $resource = new ResourceNull(
            null,
            $this->manager,
            $this->parent
        );

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('A ResourceNull has no values.');

        $resource->get('something');
    }
}
