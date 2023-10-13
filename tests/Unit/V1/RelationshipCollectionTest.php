<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\RelationshipCollection;
use PHPUnit\Framework\TestCase;

class RelationshipCollectionTest extends TestCase
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
        $object->author = new \stdClass();
        $object->author->meta = new \stdClass();

        $item = $this->createMock(Accessable::class);

        $item->method('has')
            ->with($this->equalTo('attributes.author'))
            ->willReturn(false);

        $collection = new RelationshipCollection($object, $this->manager, $item);

        $this->assertInstanceOf(RelationshipCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);
        $this->assertSame($collection->getKeys(), ['author']);

        $this->assertTrue($collection->has('author'));
        $this->assertInstanceOf(Accessable::class, $collection->get('author'));

        // test get() with not existing key throws an exception
        $this->assertFalse($collection->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this relationship collection.'
        );

        $collection->get('something');
    }

    /**
     * @test create with empty object
     */
    public function testCreateWithEmptyObject()
    {
        $item = $this->createMock(Accessable::class);

        $item->method('has')
            ->with($this->equalTo('attributes'))
            ->willReturn(false);

        $object = new \stdClass();

        $collection = new RelationshipCollection($object, $this->manager, $item);

        $this->assertInstanceOf(RelationshipCollection::class, $collection);
    }

    /**
     * @test
     *
     * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
     */
    public function testCreateWithTypePropertyThrowsException()
    {
        $item = $this->createMock(Accessable::class);

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes'))
            ->willReturn(false);

        $object = new \stdClass();
        $object->type = 'posts';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`'
        );

        $collection = new RelationshipCollection($object, $this->manager, $item);
    }

    /**
     * @test
     *
     * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
     */
    public function testCreateWithIdPropertyThrowsException()
    {
        $item = $this->createMock(Accessable::class);

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes'))
            ->will($this->returnValue(false));

        $object = new \stdClass();
        $object->id = '5';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`'
        );

        $collection = new RelationshipCollection($object, $this->manager, $item);
    }

    /**
     * @test
     *
     * In other words, a resource can not have an attribute and relationship with the same name,
     */
    public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
    {
        $item = $this->createMock(Accessable::class);

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes.author'))
            ->willReturn(true);

        $object = new \stdClass();
        $object->author = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            '"author" property cannot be set because it exists already in parents Resource object.'
        );

        $collection = new RelationshipCollection($object, $this->manager, $item);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Relationships has to be an object, "' . gettype($input) . '" given.'
        );

        $collection = new RelationshipCollection($input, $this->manager, $this->parent);
    }
}
