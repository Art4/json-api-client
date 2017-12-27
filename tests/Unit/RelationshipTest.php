<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

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

use Art4\JsonApiClient\Relationship;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class RelationshipTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
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
     * @test create with object returns self
     */
    public function testCreateWithObjectReturnsSelf()
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $relationship);

        $this->assertTrue($relationship->has('meta'));

        $meta = $relationship->get('meta');

        $this->assertSame(['meta' => $meta], $relationship->asArray());

        // test get() with not existing key throws an exception
        $this->assertFalse($relationship->has('something'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"something" doesn\'t exist in Relationship.'
        );

        $relationship->get('something');
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * The value of the relationships key MUST be an object (a "relationships object").
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        // Skip if $input is an object
        if (gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Relationship has to be an object, "' . gettype($input) . '" given.'
        );

        $relationship->parse($input);
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

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'A Relationship object MUST contain at least one of the following properties: links, data, meta'
        );

        $relationship->parse($object);
    }

    /**
     * @test create with link object
     */
    public function testCreateWithLinksObject()
    {
        $object = new \stdClass();
        $object->links = new \stdClass();
        $object->links->self = 'http://example.org/self';

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertSame($relationship->getKeys(), ['links']);
        $this->assertTrue($relationship->has('links'));

        $links = $relationship->get('links');

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLinkInterface', $links);

        $this->assertSame(['links' => $links], $relationship->asArray());
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

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierInterface', $relationship->get('data'));
    }

    /**
     * @test create with data null
     */
    public function testCreateWithDataNull()
    {
        $object = new \stdClass();
        $object->data = null;

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
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

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $resources = $relationship->get('data');

        $this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollectionInterface', $resources);
    }

    /**
     * @test create with data empty array
     */
    public function testCreateWithDataEmptyArray()
    {
        $object = new \stdClass();
        $object->data = [];

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertSame($relationship->getKeys(), ['data']);
        $this->assertTrue($relationship->has('data'));

        $resources = $relationship->get('data');

        $this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollectionInterface', $resources);
    }

    /**
     * @test create with meta object
     */
    public function testCreateWithMetaObject()
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $relationship = new Relationship($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $relationship->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
        $this->assertSame($relationship->getKeys(), ['meta']);
        $this->assertTrue($relationship->has('meta'));

        $this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $relationship->get('meta'));
    }
}
