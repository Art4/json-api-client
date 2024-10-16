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
use Art4\JsonApiClient\V1\Relationship;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RelationshipTest extends TestCase
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
     * @test create with object returns self
     */
    public function testCreateWithObjectReturnsSelf(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $relationship = new Relationship($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertInstanceOf(Accessable::class, $relationship);

        $this->assertTrue($relationship->has('meta'));

        $meta = $relationship->get('meta');

        // test get() with not existing key throws an exception
        $this->assertFalse($relationship->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in Relationship.'
        );

        $relationship->get('something');
    }

    /**
     * The value of the relationships key MUST be an object (a "relationships object").
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithoutObjectThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Relationship has to be an object, "' . gettype($input) . '" given.'
        );

        $relationship = new Relationship($input, $this->manager, $this->parent);
    }

    /**
     * @test
     *
     * A "relationship object" MUST contain at least one of the following: links, data, meta
     */
    public function testCreateWithoutLinksDataMetaPropertiesThrowsException(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'A Relationship object MUST contain at least one of the following properties: links, data, meta'
        );

        $relationship = new Relationship($object, $this->manager, $this->parent);
    }

    /**
     * @test create with link object
     */
    public function testCreateWithLinksObject(): void
    {
        $object = new \stdClass();
        $object->links = new \stdClass();
        $object->links->self = 'http://example.org/self';

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['links']);
        $this->assertTrue($relationship->has('links'));

        $links = $relationship->get('links');

        $this->assertInstanceOf(Accessable::class, $links);
    }

    /**
     * @test create with data object
     *
     * data: resource linkage, see http://jsonapi.org/format/#document-resource-object-linkage
     */
    public function testCreateWithDataObject(): void
    {
        $data = new \stdClass();
        $data->type = 'posts';
        $data->id = 5;

        $object = new \stdClass();
        $object->data = $data;

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $this->assertInstanceOf(Accessable::class, $relationship->get('data'));
    }

    /**
     * @test create with data null
     */
    public function testCreateWithDataNull(): void
    {
        $object = new \stdClass();
        $object->data = null;

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $this->assertNull($relationship->get('data'));
    }

    /**
     * @test create with data object array
     */
    public function testCreateWithDataObjectArray(): void
    {
        $data_obj = new \stdClass();
        $data_obj->type = 'types';
        $data_obj->id = 5;

        $object = new \stdClass();
        $object->data = [$data_obj];

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $resources = $relationship->get('data');

        $this->assertInstanceOf(Accessable::class, $resources);
    }

    /**
     * @test create with data empty array
     */
    public function testCreateWithDataEmptyArray(): void
    {
        $object = new \stdClass();
        $object->data = [];

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $resources = $relationship->get('data');

        $this->assertInstanceOf(Accessable::class, $resources);
    }

    /**
     * @test create with meta object
     */
    public function testCreateWithMetaObject(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['meta']);
        $this->assertTrue($relationship->has('meta'));

        $this->assertInstanceOf(Accessable::class, $relationship->get('meta'));
    }
}
