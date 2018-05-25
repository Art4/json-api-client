<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

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
use Art4\JsonApiClient\V1\ResourceItem;

class ResourceItemTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
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
        $object->type = 'type';
        $object->id = 789;

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
    public function testCreateWithFullObject()
    {
        $object = new \stdClass();
        $object->type = 'type';
        $object->id = 789;
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
    public function testTypeCannotBeAnObject()
    {
        $object = new \stdClass();
        $object->type = new \stdClass;
        $object->id = '753';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource type cannot be an array or object'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * The values of the id and type members MUST be strings.
     */
    public function testTypeCannotBeAnArray()
    {
        $object = new \stdClass();
        $object->type = [];
        $object->id = '753';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource type cannot be an array or object'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * The values of the id and type members MUST be strings.
     */
    public function testIdCannotBeAnObject()
    {
        $object = new \stdClass();
        $object->type = 'posts';
        $object->id = new \stdClass;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource id cannot be an array or object'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * The values of the id and type members MUST be strings.
     */
    public function testIdCannotBeAnArray()
    {
        $object = new \stdClass();
        $object->type = 'posts';
        $object->id = [];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Resource id cannot be an array or object'
        );

        $item = new ResourceItem($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * A "resource object" is an object that identifies an individual resource.
     * A "resource object" MUST contain type and id members.
     *
     * @param mixed $input
     */
    public function testCreateWithDataproviderThrowsException($input)
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
    public function testCreateWithObjectWithoutTypeThrowsException()
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
    public function testCreateWithObjectWithoutIdThrowsException()
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
