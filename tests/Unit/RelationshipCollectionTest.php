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

namespace Art4\JsonApiClient\Tests\Unit;

use Art4\JsonApiClient\RelationshipCollection;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class RelationshipCollectionTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->manager = $this->buildManagerMock();
    }

    /**
     * @test create with object
     */
    public function testCreateWithObject()
    {
        $object = new \stdClass();
        $object->author = new \stdClass();
        $object->author->meta = new \stdClass();

        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
            ->getMock();

        $item->method('has')
            ->with($this->equalTo('attributes.author'))
            ->willReturn(false);

        $collection = new RelationshipCollection($this->manager, $item);
        $collection->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
        $this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);
        $this->assertSame($collection->getKeys(), ['author']);

        $this->assertTrue($collection->has('author'));
        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipInterface', $collection->get('author'));

        $this->assertSame([
            'author' => $collection->get('author'),
        ], $collection->asArray());

        // test get() with not existing key throws an exception
        $this->assertFalse($collection->has('something'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"something" doesn\'t exist in this relationship collection.'
        );

        $collection->get('something');
    }

    /**
     * @test create with empty object
     */
    public function testCreateWithEmptyObject()
    {
        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
            ->getMock();

        $item->method('has')
            ->with($this->equalTo('attributes'))
            ->willReturn(false);

        $object = new \stdClass();

        $collection = new RelationshipCollection($this->manager, $item);
        $collection->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
    }

    /**
     * @test
     *
     * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
     */
    public function testCreateWithTypePropertyThrowsException()
    {
        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
            ->getMock();

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes'))
            ->willReturn(false);

        $object = new \stdClass();
        $object->type = 'posts';

        $collection = new RelationshipCollection($this->manager, $item);

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'These properties are not allowed in attributes: `type`, `id`'
        );

        $collection->parse($object);
    }

    /**
     * @test
     *
     * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
     */
    public function testCreateWithIdPropertyThrowsException()
    {
        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
            ->getMock();

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes'))
            ->will($this->returnValue(false));

        $object = new \stdClass();
        $object->id = '5';

        $collection = new RelationshipCollection($this->manager, $item);

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'These properties are not allowed in attributes: `type`, `id`'
        );

        $collection->parse($object);
    }

    /**
     * @test
     *
     * In other words, a resource can not have an attribute and relationship with the same name,
     */
    public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
    {
        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
            ->getMock();

        $item->expects($this->any())
            ->method('has')
            ->with($this->equalTo('attributes.author'))
            ->willReturn(true);

        $object = new \stdClass();
        $object->author = new \stdClass();

        $collection = new RelationshipCollection($this->manager, $item);

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            '"author" property cannot be set because it exists already in parents Resource object.'
        );

        $collection->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $item = $this->getMockBuilder('Art4\JsonApiClient\ResourceItemInterface')
        ->getMock();

        $collection = new RelationshipCollection($this->manager, $item);

        // Skip if $input is an object
        if (gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Relationships has to be an object, "' . gettype($input) . '" given.'
        );

        $collection->parse($input);
    }
}
