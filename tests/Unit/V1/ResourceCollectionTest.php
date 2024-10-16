<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\AccessKey;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\ResourceCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourceCollectionTest extends TestCase
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
     * @test create with empty array
     */
    public function testCreateWithEmptyArray(): void
    {
        $collection = new ResourceCollection([], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), []);

        // Test get() with various key types
        $this->assertFalse($collection->has(0));
        $this->assertFalse($collection->has(AccessKey::create('0')));
        $this->assertFalse($collection->has('string'));
    }

    /**
     * @test create with identifier object
     */
    public function testCreateWithIdentifier(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = 789;

        $collection = new ResourceCollection([$object], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceCollection::class, $collection);

        $this->assertSame($collection->getKeys(), [0]);

        $this->assertTrue($collection->has(0));
        $resource = $collection->get(0);

        $this->assertInstanceOf(Accessable::class, $resource);
    }

    /**
     * @test create with identifier object and meta
     */
    public function testCreateWithIdentifierAndMeta(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = 789;
        $object->meta = new \stdClass();

        $collection = new ResourceCollection(
            [$object, $object, $object],
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(ResourceCollection::class, $collection);

        $this->assertSame($collection->getKeys(), [0, 1, 2]);

        $this->assertTrue($collection->has(0));
        $resource = $collection->get(0);

        $this->assertInstanceOf(Accessable::class, $resource);

        $this->assertSame($resource, $collection->get('0'));
        $this->assertSame($resource, $collection->get(AccessKey::create('0')));

        $this->assertTrue($collection->has(1));
        $resource = $collection->get(1);

        $this->assertInstanceOf(Accessable::class, $resource);

        $this->assertTrue($collection->has(2));
        $resource = $collection->get(2);

        $this->assertInstanceOf(Accessable::class, $resource);
    }

    /**
     * @test create with item object
     */
    public function testCreateWithItem(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = 789;
        $object->attributes = new \stdClass();
        $object->relationships = new \stdClass();
        $object->links = new \stdClass();

        $collection = new ResourceCollection([$object], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceCollection::class, $collection);

        $this->assertSame($collection->getKeys(), [0]);
        $this->assertTrue($collection->has(0));

        $resource = $collection->get(0);

        $this->assertInstanceOf(Accessable::class, $resource);
    }

    #[DataProvider('jsonValuesProviderWithoutArray')]
    public function testCreateWithoutArrayThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resources for a collection has to be in an array, "' . gettype($input) . '" given.'
        );

        $collection = new ResourceCollection($input, $this->manager, $this->parent);
    }

    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithoutObjectInArrayThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resources inside a collection MUST be objects, "' . gettype($input) . '" given.'
        );

        $collection = new ResourceCollection([$input], $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @test get('resources') on an empty collection throws an exception
     */
    public function testGetResourcesWithEmptyCollectionThrowsException(): void
    {
        $collection = new ResourceCollection([], $this->manager, $this->createMock(Accessable::class));

        $this->assertInstanceOf(ResourceCollection::class, $collection);

        $this->assertFalse($collection->has(0));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"0" doesn\'t exist in this resource.'
        );

        $collection->get(0);
    }
}
