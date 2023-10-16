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
use Art4\JsonApiClient\V1\ResourceIdentifierCollection;
use PHPUnit\Framework\TestCase;

class ResourceIdentifierCollectionTest extends TestCase
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
        $collection = new ResourceIdentifierCollection([], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceIdentifierCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), []);
        $this->assertFalse($collection->has(0));
    }

    /**
     * @test create with identifier object
     */
    public function testCreateWithIdentifier(): void
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = 789;
        $object->meta = new \stdClass();

        $collection = new ResourceIdentifierCollection(
            [$object, $object, $object],
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(ResourceIdentifierCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), [0, 1, 2]);

        $this->assertTrue($collection->has(0));
        $this->assertTrue($collection->has(1));
        $this->assertTrue($collection->has(2));
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

        $collection = new ResourceIdentifierCollection([$object], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceIdentifierCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), [0]);
        $this->assertTrue($collection->has(0));

        $resource = $collection->get(0);

        $this->assertInstanceOf(Accessable::class, $resource);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutArray
     *
     * @param mixed $input
     */
    public function testCreateWithoutArrayThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resources for a collection has to be in an array, "' . gettype($input) . '" given.'
        );

        $collection = new ResourceIdentifierCollection($input, $this->manager, $this->parent);
    }

    /**
     * @test get('resources') on an empty identifier collection throws an exception
     */
    public function testGetResourcesWithEmptyCollectionThrowsException(): void
    {
        $collection = new ResourceIdentifierCollection([], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceIdentifierCollection::class, $collection);

        $this->assertFalse($collection->has(0));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"0" doesn\'t exist in this resource.'
        );

        $collection->get(0);
    }
}
