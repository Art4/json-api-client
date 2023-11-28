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
use Art4\JsonApiClient\V1\ResourceItem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourceItemTest extends TestCase
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

        $this->manager->method('getParam')->willReturn(false);

        $item = new ResourceItem($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceItem::class, $item);
        $this->assertInstanceOf(Accessable::class, $item);
        $this->assertSame($item->getKeys(), ['type', 'id']);

        $this->assertSame($item->get('type'), 'type');
        $this->assertSame($item->get('id'), '789');
        $this->assertFalse($item->has('meta'));
        $this->assertFalse($item->has('attributes'));
        $this->assertFalse($item->has('relationships'));
        $this->assertFalse($item->has('links'));

        // test get() with not existing key throws an exception
        $this->assertFalse($item->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this resource.'
        );

        $item->get('something');
    }

    /**
     * @test create with full object
     */
    public function testCreateWithFullObject(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = '789';
        $object->meta = new \stdClass();
        $object->attributes = new \stdClass();
        $object->relationships = new \stdClass();
        $object->links = new \stdClass();

        $item = new ResourceItem($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceItem::class, $item);

        $this->assertSame($item->get('type'), 'type');
        $this->assertSame($item->get('id'), '789');
        $this->assertTrue($item->has('meta'));
        $this->assertInstanceOf(Accessable::class, $item->get('meta'));
        $this->assertTrue($item->has('attributes'));
        $this->assertInstanceOf(Accessable::class, $item->get('attributes'));
        $this->assertTrue($item->has('relationships'));
        $this->assertInstanceOf(Accessable::class, $item->get('relationships'));
        $this->assertTrue($item->has('links'));
        $this->assertInstanceOf(Accessable::class, $item->get('links'));
        $this->assertSame($item->getKeys(), ['type', 'id', 'meta', 'attributes', 'relationships', 'links']);
    }

    /**
     * The values of the id and type members MUST be strings.
     */
    #[DataProvider('jsonValuesProviderWithoutString')]
    public function testTypeMustBeAString(mixed $input): void
    {
        $object = new \stdClass();
        $object->type = $input;
        $object->id = '753';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource type MUST be a string'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * The values of the id and type members MUST be strings.
     */
    #[DataProvider('jsonValuesProviderWithoutString')]
    public function testIdMustBeAString(mixed $input): void
    {
        $object = new \stdClass();
        $object->type = 'posts';
        $object->id = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource id MUST be a string'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * A "resource object" is an object that identifies an individual resource.
     * A "resource object" MUST contain type and id members.
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithDataproviderThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource has to be an object, "' . gettype($input) . '" given.'
        );

        $item = new ResourceItem($input, $this->manager, $this->parent);
    }

    /**
     * @test A "resource object" MUST contain type and id members.
     */
    public function testCreateWithObjectWithoutTypeThrowsException(): void
    {
        $object = new \stdClass();
        $object->id = 123;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource object MUST contain a type'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * @test A "resource object" MUST contain type and id members.
     */
    public function testCreateWithObjectWithoutIdThrowsException(): void
    {
        $object = new \stdClass();
        $object->type = 'type';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A resource object MUST contain an id'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }
}
