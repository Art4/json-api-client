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

use Art4\JsonApiClient\RelationshipLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class RelationshipLinkTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->manager = $this->buildManagerMock();

        // Mock identifier collection
        $collection = $this->getMockBuilder('Art4\JsonApiClient\ResourceIdentifierCollectionInterface')
            ->getMock();

        // Mock Relationship with data
        $this->relationship = $this->getMockBuilder('Art4\JsonApiClient\RelationshipInterface')
            ->getMock();

        $this->relationship->expects($this->any())
            ->method('has')
            ->with($this->equalTo('data'))
            ->will($this->returnValue(true));

        $this->relationship->expects($this->any())
            ->method('get')
            ->with($this->equalTo('data'))
            ->will($this->returnValue($collection));
    }

    /**
     * @test only self, related and pagination property can exist
     *
     * links: a links object containing at least one of the following:
     * - self: a link for the relationship itself (a "relationship link"). This link allows
     *   the client to directly manipulate the relationship. For example, it would allow a
     *   client to remove an author from an article without deleting the people resource itself.
     * - related: a related resource link
     *
     * A relationship object that represents a to-many relationship MAY also contain pagination
     * links under the links member, as described below.
     */
    public function testOnlySelfRelatedPaginationPropertiesExists()
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->related = 'http://example.org/related';
        $object->first = 'http://example.org/first';
        $object->last = 'http://example.org/last';
        $object->prev = 'http://example.org/prev';
        $object->next = 'http://example.org/next';
        $object->custom = 'http://example.org/custom';
        $object->meta = 'http://example.org/meta';

        $link = new RelationshipLink($this->manager, $this->relationship);
        $link->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);
        $this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
        $this->assertSame($link->getKeys(), ['self', 'related', 'first', 'last', 'prev', 'next', 'custom', 'meta']);

        $this->assertTrue($link->has('custom'));
        $this->assertSame($link->get('custom'), 'http://example.org/custom');
        $this->assertTrue($link->has('meta'));
        $this->assertSame($link->get('meta'), 'http://example.org/meta');
        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('related'));
        $this->assertSame($link->get('related'), 'http://example.org/related');
        $this->assertTrue($link->has('first'));
        $this->assertSame($link->get('first'), 'http://example.org/first');
        $this->assertTrue($link->has('last'));
        $this->assertSame($link->get('last'), 'http://example.org/last');
        $this->assertTrue($link->has('prev'));
        $this->assertSame($link->get('prev'), 'http://example.org/prev');
        $this->assertTrue($link->has('next'));
        $this->assertSame($link->get('next'), 'http://example.org/next');

        $this->assertSame($link->asArray(), [
            'self' => $link->get('self'),
            'related' => $link->get('related'),
            'first' => $link->get('first'),
            'last' => $link->get('last'),
            'last' => $link->get('last'),
            'prev' => $link->get('prev'),
            'next' => $link->get('next'),
            'custom' => $link->get('custom'),
            'meta' => $link->get('meta'),
        ]);

        // Test full array
        $this->assertSame($link->asArray(true), [
            'self' => $link->get('self'),
            'related' => $link->get('related'),
            'first' => $link->get('first'),
            'last' => $link->get('last'),
            'prev' => $link->get('prev'),
            'next' => $link->get('next'),
            'custom' => $link->get('custom'),
            'meta' => $link->get('meta'),
        ]);
    }

    /**
     * @test pagination links are parsed, if data in parent relationship object exists
     */
    public function testPaginationParsedIfRelationshipDataExists()
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        $link = new RelationshipLink($this->manager, $this->relationship);

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "first" has to be a string or null, "object" given.'
        );

        $link->parse($object);
    }

    /**
     * @test pagination links are not parsed, if data in parent relationship object doesnt exist
     */
    public function testPaginationNotParsedIfRelationshipDataNotExists()
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        // Mock Relationship
        $relationship = $this->getMockBuilder('Art4\JsonApiClient\RelationshipInterface')
            ->getMock();

        $relationship->expects($this->any())
            ->method('has')
            ->with($this->equalTo('data'))
            ->will($this->returnValue(false));

        $link = new RelationshipLink($this->manager, $relationship);
        $link->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);
        $this->assertSame($link->getKeys(), ['self', 'first', 'last', 'prev', 'next']);

        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('first'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('first'));
        $this->assertTrue($link->has('last'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('last'));
        $this->assertTrue($link->has('prev'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('prev'));
        $this->assertTrue($link->has('next'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('next'));
    }

    /**
     * @test pagination links are not parsed, if data in parent relationship object is not IdentifierCollection
     */
    public function testPaginationNotParsedIfRelationshipIdentifierCollectionNotExists()
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        // Mock Relationship
        $relationship = $this->getMockBuilder('Art4\JsonApiClient\RelationshipInterface')
            ->getMock();

        $relationship->expects($this->any())
            ->method('has')
            ->with($this->equalTo('data'))
            ->will($this->returnValue(true));

        // Mock identifier item
        $data = $this->getMockBuilder('Art4\JsonApiClient\ResourceIdentifierInterface')
            ->getMock();

        $relationship->expects($this->any())
            ->method('get')
            ->with($this->equalTo('data'))
            ->will($this->returnValue($data));

        $link = new RelationshipLink($this->manager, $relationship);
        $link->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);
        $this->assertSame($link->getKeys(), ['self', 'first', 'last', 'prev', 'next']);

        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('first'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('first'));
        $this->assertTrue($link->has('last'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('last'));
        $this->assertTrue($link->has('prev'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('prev'));
        $this->assertTrue($link->has('next'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('next'));
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * links: a links object containing at least one of the following:
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be an object
        if (gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'RelationshipLink has to be an object, "' . gettype($input) . '" given.'
        );

        $link->parse($input);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * test create without object or string attribute throws exception
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
    {
        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be an object
        if (gettype($input) === 'string' or gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);

            return;
        }

        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->input = $input;

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @test object contains at least one of the following: self, related
     */
    public function testCreateWithoutSelfAndRelatedPropertiesThrowsException()
    {
        $object = new \stdClass();
        $object->first = 'http://example.org/first';
        $object->next = 'http://example.org/next';

        $link = new RelationshipLink($this->manager, $this->relationship);

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'RelationshipLink has to be at least a "self" or "related" link'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * self: a link for the relationship itself (a "relationship link").
     *
     * @param mixed $input
     */
    public function testSelfMustBeAString($input)
    {
        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be a string
        if (gettype($input) === 'string') {
            $this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);

            return;
        }

        $object = new \stdClass();
        $object->self = $input;

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "self" has to be a string, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * related: a related resource link when the primary data represents a resource relationship.
     * If present, a related resource link MUST reference a valid URL
     *
     * @param mixed $input
     */
    public function testRelatedMustBeAStringOrObject($input)
    {
        $object = new \stdClass();
        $object->related = $input;

        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be a string or object
        if (gettype($input) === 'string' or gettype($input) === 'object') {
            $link->parse($object);

            $this->assertTrue($link->has('related'));

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "related" has to be a string or object, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testFirstCanBeAStringOrNull($input)
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->first = $input;

        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self', 'first']);

            $this->assertTrue($link->has('first'));
            $this->assertSame($link->get('first'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('first'));

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "first" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testLastCanBeAStringOrNull($input)
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->last = $input;

        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self', 'last']);

            $this->assertTrue($link->has('last'));
            $this->assertSame($link->get('last'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('last'));

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "last" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testPrevCanBeAStringOrNull($input)
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->prev = $input;

        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self', 'prev']);

            $this->assertTrue($link->has('prev'));
            $this->assertSame($link->get('prev'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('prev'));

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "prev" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testNextCanBeAStringOrNull($input)
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->next = $input;

        $link = new RelationshipLink($this->manager, $this->relationship);

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self', 'next']);

            $this->assertTrue($link->has('next'));
            $this->assertSame($link->get('next'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link->parse($object);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('next'));

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'property "next" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException()
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->related = 'http://example.org/related';

        $link = new RelationshipLink($this->manager, $this->relationship);
        $link->parse($object);

        $this->assertFalse($link->has('something'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
