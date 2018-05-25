<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Tests\Unit\Serializer;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

class ArraySerializerTest extends TestCase
{
    /**
     * @test non-recursive serialize()
     */
    public function testSerialize()
    {
        $object1 = $this->createMock(Accessable::class);
        $object2 = new \stdClass;

        $data = $this->createMock(Accessable::class);
        $data->method('get')->will($this->returnValueMap([
            ['AccessObject', $object1],
            ['object', $object2],
            ['array', []],
            ['string', 'string'],
            ['integer', 1],
            ['boolean', true],
            ['null', null],
        ]));
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
    public function testRecursiveSerialize()
    {
        $stdObject = new \stdClass;
        $stdObject->key = 'value';

        $object1 = $this->createMock(Accessable::class);
        $object1->method('get')->will($this->returnValueMap([
            ['object', $stdObject],
            ['array', []],
            ['string', 'string'],
            ['integer', 1],
            ['boolean', true],
            ['null', null],
        ]));
        $object1->method('getKeys')->willReturn([
            'object',
            'array',
            'string',
            'integer',
            'boolean',
            'null',
        ]);

        $data = $this->createMock(Accessable::class);
        $data->method('get')->will($this->returnValueMap([
            ['AccessObject', $object1],
        ]));
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
