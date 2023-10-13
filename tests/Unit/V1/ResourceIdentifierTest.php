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
use Art4\JsonApiClient\V1\ResourceIdentifier;
use PHPUnit\Framework\TestCase;

class ResourceIdentifierTest extends TestCase
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
    public function testCreateWithObject(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = '789';

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceIdentifier::class, $identifier);
        $this->assertInstanceOf(Accessable::class, $identifier);
        $this->assertSame($identifier->getKeys(), ['type', 'id']);

        $this->assertSame($identifier->get('type'), 'type');
        $this->assertSame($identifier->get('id'), '789');
        $this->assertFalse($identifier->has('meta'));
    }

    /**
     * @test create with object and meta
     */
    public function testCreateWithObjectAndMeta(): void
    {
        $object = new \stdClass();
        $object->type = 'types';
        $object->id = '159';
        $object->meta = new \stdClass();

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceIdentifier::class, $identifier);

        $this->assertSame($identifier->get('type'), 'types');
        $this->assertSame($identifier->get('id'), '159');
        $this->assertTrue($identifier->has('meta'));
        $this->assertInstanceOf(Accessable::class, $identifier->get('meta'));
        $this->assertSame($identifier->getKeys(), ['type', 'id', 'meta']);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * The values of the id and type members MUST be strings.
     *
     * @param mixed $input
     */
    public function testTypeMustBeAString($input): void
    {
        $object = new \stdClass();
        $object->type = $input;
        $object->id = '753';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource type MUST be a string'
        );

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);

        $this->assertIsString($identifier->get('type'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * The values of the id and type members MUST be strings.
     *
     * @param mixed $input
     */
    public function testIdMustBeAString($input): void
    {
        $object = new \stdClass();
        $object->type = 'posts';
        $object->id = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource id MUST be a string'
        );

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * A "resource identifier object" is an object that identifies an individual resource.
     * A "resource identifier object" MUST contain type and id members.
     *
     * @param mixed $input
     */
    public function testCreateWithDataproviderThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource has to be an object, "' . gettype($input) . '" given.'
        );

        $identifier = new ResourceIdentifier($input, $this->manager, $this->parent);
    }

    /**
     * @test A "resource identifier object" MUST contain type and id members.
     */
    public function testCreateWithObjectWithoutTypeThrowsException(): void
    {
        $object = new \stdClass();
        $object->id = '123';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource object MUST contain a type'
        );

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);
    }

    /**
     * @test A "resource identifier object" MUST contain type and id members.
     */
    public function testCreateWithObjectWithoutIdThrowsException(): void
    {
        $object = new \stdClass();
        $object->type = 'type';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource object MUST contain an id'
        );

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);
    }

    /**
     * @test get() on an undefined value throws Exception
     */
    public function testGetWithUndefinedValueThrowsException(): void
    {
        $object = new \stdClass();
        $object->type = 'posts';
        $object->id = '9';

        $identifier = new ResourceIdentifier($object, $this->manager, $this->parent);

        $this->assertFalse($identifier->has('foobar'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"foobar" doesn\'t exist in this identifier.'
        );

        $identifier->get('foobar');
    }
}
