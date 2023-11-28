<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Helper;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Tests\Fixtures\AccessableTraitMock;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AccessableTraitTest extends TestCase
{
    use HelperTrait;

    #[DataProvider('jsonValuesProviderWithoutStringAndInt')]
    public function testHasWithInvalidKeyTypeTriggersDeprecationError(mixed $key): void
    {
        $resource = new AccessableTraitMock();

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr) use ($key): bool {
                $this->assertSame(
                    'Art4\JsonApiClient\Tests\Fixtures\AccessableTraitMock::has(): Providing Argument #1 ($key) as `' . gettype($key) . '` is deprecated since 1.2.0, please provide as `int|string` instead.',
                    $errstr
                );

                restore_error_handler();
                return true;
            },
            E_USER_DEPRECATED
        );

        $resource->has($key);
    }

    #[DataProvider('jsonValuesProviderWithoutStringAndInt')]
    public function testGetWithInvalidKeyTypeTriggersDeprecationError(mixed $key): void
    {
        $resource = new AccessableTraitMock();

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr) use ($key): bool {
                $this->assertSame(
                    'Art4\JsonApiClient\Tests\Fixtures\AccessableTraitMock::get(): Providing Argument #1 ($key) as `' . gettype($key) . '` is deprecated since 1.2.0, please provide as `int|string` instead.',
                    $errstr
                );

                restore_error_handler();
                return true;
            },
            E_USER_DEPRECATED
        );

        try {
            $resource->get($key);
        } catch (AccessException $th) {
            // ignore AccessException
        }
    }
}
