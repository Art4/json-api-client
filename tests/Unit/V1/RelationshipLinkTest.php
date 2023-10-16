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
use Art4\JsonApiClient\V1\RelationshipLink;
use PHPUnit\Framework\TestCase;

class RelationshipLinkTest extends TestCase
{
    use HelperTrait;

    private Accessable $relationship;

    /**
     * @setup
     */
    public function setUp(): void
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);

        // Mock identifier collection
        $collection = new ResourceIdentifierCollection([], $this->manager, $this->parent);

        // Mock Relationship with data
        $this->relationship = $this->createMock(Accessable::class);

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
    public function testOnlySelfRelatedPaginationPropertiesExists(): void
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

        $link = new RelationshipLink($object, $this->manager, $this->relationship);

        $this->assertInstanceOf(RelationshipLink::class, $link);
        $this->assertInstanceOf(Accessable::class, $link);
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
    }

    /**
     * @test pagination links are parsed, if data in parent relationship object exists
     */
    public function testPaginationParsedIfRelationshipDataExists(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "first" has to be a string or null, "object" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @test pagination links are not parsed, if data in parent relationship object doesnt exist
     */
    public function testPaginationNotParsedIfRelationshipDataNotExists(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        // Mock Relationship
        $relationship = $this->createMock(Accessable::class);

        $relationship->expects($this->any())
            ->method('has')
            ->with($this->equalTo('data'))
            ->will($this->returnValue(false));

        $link = new RelationshipLink($object, $this->manager, $relationship);

        $this->assertInstanceOf(RelationshipLink::class, $link);
        $this->assertSame($link->getKeys(), ['self', 'first', 'last', 'prev', 'next']);

        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('first'));
        $this->assertInstanceOf(Accessable::class, $link->get('first'));
        $this->assertTrue($link->has('last'));
        $this->assertInstanceOf(Accessable::class, $link->get('last'));
        $this->assertTrue($link->has('prev'));
        $this->assertInstanceOf(Accessable::class, $link->get('prev'));
        $this->assertTrue($link->has('next'));
        $this->assertInstanceOf(Accessable::class, $link->get('next'));
    }

    /**
     * @test pagination links are not parsed, if data in parent relationship object is not IdentifierCollection
     */
    public function testPaginationNotParsedIfRelationshipIdentifierCollectionNotExists(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->first = new \stdClass();
        $object->last = new \stdClass();
        $object->prev = new \stdClass();
        $object->next = new \stdClass();

        // Mock Relationship
        $relationship = $this->createMock(Accessable::class);

        $relationship->expects($this->any())
            ->method('has')
            ->with($this->equalTo('data'))
            ->will($this->returnValue(true));

        // Mock identifier item
        $data = $this->createMock(Accessable::class);

        $relationship->expects($this->any())
            ->method('get')
            ->with($this->equalTo('data'))
            ->will($this->returnValue($data));

        $link = new RelationshipLink($object, $this->manager, $relationship);

        $this->assertInstanceOf(RelationshipLink::class, $link);
        $this->assertSame($link->getKeys(), ['self', 'first', 'last', 'prev', 'next']);

        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('first'));
        $this->assertInstanceOf(Accessable::class, $link->get('first'));
        $this->assertTrue($link->has('last'));
        $this->assertInstanceOf(Accessable::class, $link->get('last'));
        $this->assertTrue($link->has('prev'));
        $this->assertInstanceOf(Accessable::class, $link->get('prev'));
        $this->assertTrue($link->has('next'));
        $this->assertInstanceOf(Accessable::class, $link->get('next'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * links: a links object containing at least one of the following:
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'RelationshipLink has to be an object, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($input, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * test create without object or string attribute throws exception
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->input = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @test object contains at least one of the following: self, related
     */
    public function testCreateWithoutSelfAndRelatedPropertiesThrowsException(): void
    {
        $object = new \stdClass();
        $object->first = 'http://example.org/first';
        $object->next = 'http://example.org/next';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'RelationshipLink has to be at least a "self" or "related" link'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * self: a link for the relationship itself (a "relationship link").
     *
     * @param mixed $input
     */
    public function testSelfMustBeAStringOrObject($input): void
    {
        $object = new \stdClass();
        $object->self = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "self" has to be a string or object, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * related: a related resource link when the primary data represents a resource relationship.
     * If present, a related resource link MUST reference a valid URL
     *
     * @param mixed $input
     */
    public function testRelatedMustBeAStringOrObject($input): void
    {
        $object = new \stdClass();
        $object->related = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "related" has to be a string or object, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testFirstCanBeAStringOrNull($input): void
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->first = $input;

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self', 'first']);

            $this->assertTrue($link->has('first'));
            $this->assertSame($link->get('first'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('first'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "first" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testLastCanBeAStringOrNull($input): void
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->last = $input;

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self', 'last']);

            $this->assertTrue($link->has('last'));
            $this->assertSame($link->get('last'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('last'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "last" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testPrevCanBeAStringOrNull($input): void
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->prev = $input;

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self', 'prev']);

            $this->assertTrue($link->has('prev'));
            $this->assertSame($link->get('prev'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('prev'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "prev" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testNextCanBeAStringOrNull($input): void
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->next = $input;

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self', 'next']);

            $this->assertTrue($link->has('next'));
            $this->assertSame($link->get('next'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new RelationshipLink($object, $this->manager, $this->relationship);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('next'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "next" has to be a string or null, "' . gettype($input) . '" given.'
        );

        $link = new RelationshipLink($object, $this->manager, $this->relationship);
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->related = 'http://example.org/related';

        $link = new RelationshipLink($object, $this->manager, $this->relationship);

        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
