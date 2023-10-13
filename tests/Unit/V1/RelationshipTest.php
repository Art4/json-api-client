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
use Art4\JsonApiClient\V1\Relationship;
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
    public function testCreateWithObjectReturnsSelf()
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
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * The value of the relationships key MUST be an object (a "relationships object").
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
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
    public function testCreateWithoutLinksDataMetaPropertiesThrowsException()
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
    public function testCreateWithLinksObject()
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
    public function testCreateWithDataObject()
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
    public function testCreateWithDataNull()
    {
        $object = new \stdClass();
        $object->data = null;

        $relationship = $relationship = new Relationship($object, $this->manager, $this->parent);
        ;

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $this->assertTrue(is_null($relationship->get('data')));
    }

    /**
     * @test create with data object array
     */
    public function testCreateWithDataObjectArray()
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
    public function testCreateWithDataEmptyArray()
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
    public function testCreateWithMetaObject()
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
