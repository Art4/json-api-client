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
use Art4\JsonApiClient\V1\ResourceCollection;

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
    public function testCreateWithEmptyArray()
    {
        $collection = new ResourceCollection([], $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), []);
        $this->assertFalse($collection->has(0));

        // Test get() with various key types
        $this->assertFalse($collection->has(new \stdClass()));
        $this->assertFalse($collection->has([]));
        $this->assertFalse($collection->has('string'));
    }

    /**
     * @test create with identifier object
     */
    public function testCreateWithIdentifier()
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
    public function testCreateWithIdentifierAndMeta()
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
    public function testCreateWithItem()
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

    /**
     * @dataProvider jsonValuesProviderWithoutArray
     *
     * @param mixed $input
     */
    public function testCreateWithoutArrayThrowsException($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resources for a collection has to be in an array, "' . gettype($input) . '" given.'
        );

        $collection = new ResourceCollection($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectInArrayThrowsException($input)
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
    public function testGetResourcesWithEmptyCollectionThrowsException()
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
