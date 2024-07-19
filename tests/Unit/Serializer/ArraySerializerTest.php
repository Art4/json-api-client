<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Serializer;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use PHPUnit\Framework\TestCase;

class ArraySerializerTest extends TestCase
{
    /**
     * @test non-recursive serialize()
     */
    public function testSerialize(): void
    {
        $object1 = $this->createMock(Accessable::class);
        $object2 = new \stdClass();

        $data = $this->createMock(Accessable::class);
        $data->method('get')->willReturnMap([
            ['AccessObject', $object1],
            ['object', $object2],
            ['array', []],
            ['string', 'string'],
            ['integer', 1],
            ['boolean', true],
            ['null', null],
        ]);
        $data->method('getKeys')->willReturn([
            'AccessObject',
            'object',
            'array',
            'string',
            'integer',
            'boolean',
            'null',
        ]);

        $serializer = new ArraySerializer();

        $this->assertSame([
            'AccessObject' => $object1,
            'object' => $object2,
            'array' => [],
            'string' => 'string',
            'integer' => 1,
            'boolean' => true,
            'null' => null,
        ], $serializer->serialize($data));
    }

    /**
     * @test recursive serialize()
     */
    public function testRecursiveSerialize(): void
    {
        $stdObject = new \stdClass();
        $stdObject->key = 'value';

        $object1 = $this->createMock(Accessable::class);
        $object1->method('get')->willReturnMap([
            ['object', $stdObject],
            ['array', []],
            ['string', 'string'],
            ['integer', 1],
            ['boolean', true],
            ['null', null],
        ]);
        $object1->method('getKeys')->willReturn([
            'object',
            'array',
            'string',
            'integer',
            'boolean',
            'null',
        ]);

        $data = $this->createMock(Accessable::class);
        $data->method('get')->willReturnMap([
            ['AccessObject', $object1],
        ]);
        $data->method('getKeys')->willReturn([
            'AccessObject',
        ]);

        $serializer = new ArraySerializer(['recursive' => true]);

        $this->assertSame([
            'AccessObject' => [
                'object' => [
                    'key' => 'value',
                ],
                'array' => [],
                'string' => 'string',
                'integer' => 1,
                'boolean' => true,
                'null' => null,
            ],
        ], $serializer->serialize($data));
    }
}
