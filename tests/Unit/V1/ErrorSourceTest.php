<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\ErrorSource;
use PHPUnit\Framework\TestCase;

class ErrorSourceTest extends TestCase
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
     * @test only 'about' property' can exist
     *
     * source: an object containing references to the source of the error, optionally including any of the following members:
     * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     * - parameter: a string indicating which query parameter caused the error.
     */
    public function testOnlyPointerParameterPropertiesExists(): void
    {
        $object = new \stdClass();
        $object->pointer = '/pointer';
        $object->parameter = 'parameter';
        $object->ignore = 'must be ignored';

        $source = new ErrorSource($object, $this->manager, $this->parent);

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
    public function testCreateWithoutObjectThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'ErrorSource has to be an object, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     *
     * @param mixed $input
     */
    public function testPointerMustBeAString($input): void
    {
        $object = new \stdClass();
        $object->pointer = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "pointer" has to be a string, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * parameter: a string indicating which query parameter caused the error.
     *
     * @param mixed $input
     */
    public function testParameterMustBeAString($input): void
    {
        $object = new \stdClass();
        $object->parameter = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "parameter" has to be a string, "' . gettype($input) . '" given.'
        );

        $source = new ErrorSource($object, $this->manager, $this->parent);
    }
}
