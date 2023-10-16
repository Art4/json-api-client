<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\BC\Serializer;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Serializer\Serializer;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    /**
     * This test will test a custom implementation of the Serializer interface.
     * DO NOT CHANGE THIS!
     * Changes are only allowed by increasing the major version number.
     */
    public function testBcForSerializerInterface(): void
    {
        $class = new class () implements Serializer {
            /**
             * Serialize data
             *
             * @param \Art4\JsonApiClient\Accessable $data The data for serialization
             *
             * @return array<string, mixed>
             */
            public function serialize(Accessable $data)
            {
                throw new \Exception('not implemented');
            }
        };

        $this->assertInstanceOf(Serializer::class, $class);
    }
}
