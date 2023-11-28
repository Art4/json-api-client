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
     */
    public function testCreateWithDataProvider(mixed $input): void
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

    public function testHasWithObjectAsKeyTriggersException(): void
    {
        $resource = new ResourceNull(
            null,
            $this->manager,
            $this->parent
        );

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr): bool {
                $this->assertSame(
                    'Art4\JsonApiClient\V1\ResourceNull::has(): Providing Argument #1 ($key) as `object` is deprecated since 1.2.0, please provide as `int|string` instead.',
                    $errstr
                );

                restore_error_handler();
                return true;
            },
            E_USER_DEPRECATED
        );

        $resource->has(new \stdClass());
    }

    public function testHasWithArrayAsKeyTriggersException(): void
    {
        $resource = new ResourceNull(
            null,
            $this->manager,
            $this->parent
        );

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr): bool {
                $this->assertSame(
                    'Art4\JsonApiClient\V1\ResourceNull::has(): Providing Argument #1 ($key) as `array` is deprecated since 1.2.0, please provide as `int|string` instead.',
                    $errstr
                );

                restore_error_handler();
                return true;
            },
            E_USER_DEPRECATED
        );

        $resource->has([]);
    }
}
