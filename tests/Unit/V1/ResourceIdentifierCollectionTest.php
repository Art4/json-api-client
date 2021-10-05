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
use Art4\JsonApiClient\V1\ResourceIdentifierCollection;

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
    public function testCreateWithEmptyArray()
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
    public function testCreateWithIdentifier()
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
    public function testCreateWithItem()
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
    public function testCreateWithoutArrayThrowsException($input)
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
    public function testGetResourcesWithEmptyCollectionThrowsException()
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
