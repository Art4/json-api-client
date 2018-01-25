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

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\ErrorSource;

class ErrorSourceTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();
    }

    /**
     * @test only 'about' property' can exist
     *
     * source: an object containing references to the source of the error, optionally including any of the following members:
     * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     * - parameter: a string indicating which query parameter caused the error.
     */
    public function testOnlyPointerParameterPropertiesExists()
    {
        $object = new \stdClass();
        $object->pointer = '/pointer';
        $object->parameter = 'parameter';
        $object->ignore = 'must be ignored';

        $source = new ErrorSource($object, $this->manager, $this->createMock(Accessable::class));

        $this->assertInstanceOf(ErrorSource::class, $source);
        $this->assertInstanceOf(Accessable::class, $source);
        $this->assertSame($source->getKeys(), ['pointer', 'parameter']);

        $this->assertFalse($source->has('ignore'));
        $this->assertTrue($source->has('pointer'));
        $this->assertSame($source->get('pointer'), '/pointer');
        $this->assertTrue($source->has('parameter'));
        $this->assertSame($source->get('parameter'), 'parameter');

        // test get() with not existing key throws an exception
        $this->assertFalse($source->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this error source.'
        );

        $source->get('something');
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * source: an object containing references to ...
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'ErrorSource has to be an object, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($input, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     *
     * @param mixed $input
     */
    public function testPointerMustBeAString($input)
    {
        $object = new \stdClass();
        $object->pointer = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "pointer" has to be a string, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($object, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * parameter: a string indicating which query parameter caused the error.
     *
     * @param mixed $input
     */
    public function testParameterMustBeAString($input)
    {
        $object = new \stdClass();
        $object->parameter = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "parameter" has to be a string, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($object, $this->manager, $this->createMock(Accessable::class));
    }
}
