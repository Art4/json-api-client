<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Helper;

use Art4\JsonApiClient\Helper\AccessableTrait;
use Art4\JsonApiClient\Accessable;
use PHPUnit\Framework\TestCase;

class AccessableTraitTest extends TestCase
{
    public function testHasWithObjectAsKeyTriggersException(): void
    {
        /** @var Accessable */
        $resource = $this->getMockForTrait(AccessableTrait::class);

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr): bool {
                $this->assertStringEndsWith(
                    '::has(): Providing Argument #1 ($key) as object is deprecated since 1.2.0, please provide as int|string|Art4\JsonApiClient\Helper\AccessKey instead.',
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
        /** @var Accessable */
        $resource = $this->getMockForTrait(AccessableTrait::class);

        // PHPUnit 10 compatible way to test trigger_error().
        set_error_handler(
            function ($errno, $errstr): bool {
                $this->assertStringEndsWith(
                    '::has(): Providing Argument #1 ($key) as array is deprecated since 1.2.0, please provide as int|string|Art4\JsonApiClient\Helper\AccessKey instead.',
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
