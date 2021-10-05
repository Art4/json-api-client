<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2021  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\Jsonapi;

class JsonapiTest extends TestCase
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
     * @test create with object
     */
    public function testCreateWithObject()
    {
        $object = new \stdClass();
        $object->version = '1.0';

        // This object MAY also contain a meta member, whose value is a meta object
        $object->meta = new \stdClass();

        // these properties must be ignored
        $object->testobj = new \stdClass();
        $object->teststring = 'http://example.org/link';

        $jsonapi = new Jsonapi($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Jsonapi::class, $jsonapi);
        $this->assertInstanceOf(Accessable::class, $jsonapi);
        $this->assertSame($jsonapi->getKeys(), ['version', 'meta']);

        $this->assertFalse($jsonapi->has('testobj'));
        $this->assertFalse($jsonapi->has('teststring'));
        $this->assertTrue($jsonapi->has('version'));
        $this->assertSame($jsonapi->get('version'), '1.0');
        $this->assertTrue($jsonapi->has('meta'));
        $this->assertInstanceOf(Accessable::class, $jsonapi->get('meta'));

        // test get() with not existing key throws an exception
        $this->assertFalse($jsonapi->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this jsonapi object.'
        );

        $jsonapi->get('something');
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * If present, the value of the jsonapi member MUST be an object (a "jsonapi object").
     *
     * @param mixed $input
     */
    public function testCreateWithDataprovider($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Jsonapi has to be an object, "' . gettype($input) . '" given.'
        );

        $jsonapi = new Jsonapi($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * The jsonapi object MAY contain a version member whose value is a string
     *
     * @param mixed $input
     */
    public function testVersionCannotBeAnObjectOrArray($input)
    {
        $object = new \stdClass();
        $object->version = $input;

        if (gettype($input) === 'object' or gettype($input) === 'array') {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage(
                'property "version" cannot be an object or array, "' . gettype($input) . '" given.'
            );

            // $jsonapi->parse($object);
            //
            // return;
        }

        $jsonapi = new Jsonapi($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Jsonapi::class, $jsonapi);
        $this->assertSame($jsonapi->getKeys(), ['version']);

        // other input must be transformed to string
        $this->assertTrue($jsonapi->has('version'));
        $this->assertTrue(is_string($jsonapi->get('version')));
    }
}
