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
use Art4\JsonApiClient\V1\DocumentLink;
use PHPUnit\Framework\TestCase;

class DocumentLinkTest extends TestCase
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

        $this->parent->expects($this->any())
            ->method('has')
            ->with('data')
            ->will($this->returnValue(true));
    }

    /**
     * test that only 'about' property' can exist
     *
     * The top-level links object MAY contain the following members:
     * - self: the link that generated the current response document.
     * - related: a related resource link when the primary data represents a resource relationship.
     * - pagination links for the primary data.
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


        $link = new DocumentLink($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Accessable::class, $link);
        $this->assertSame([
            'self',
            'related',
            'first',
            'last',
            'prev',
            'next',
            'custom',
            'meta',
        ], $link->getKeys());

        $this->assertTrue($link->has('custom'));
        $this->assertSame('http://example.org/custom', $link->get('custom'));
        $this->assertTrue($link->has('meta'));
        $this->assertSame('http://example.org/meta', $link->get('meta'));
        $this->assertTrue($link->has('self'));
        $this->assertSame('http://example.org/self', $link->get('self'));
        $this->assertTrue($link->has('related'));
        $this->assertSame('http://example.org/related', $link->get('related'));
        $this->assertTrue($link->has('first'));
        $this->assertSame('http://example.org/first', $link->get('first'));
        $this->assertTrue($link->has('last'));
        $this->assertSame('http://example.org/last', $link->get('last'));
        $this->assertTrue($link->has('prev'));
        $this->assertSame('http://example.org/prev', $link->get('prev'));
        $this->assertTrue($link->has('next'));
        $this->assertSame('http://example.org/next', $link->get('next'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * links: a links object related to the primary data.
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'DocumentLink has to be an object, "' . gettype($input) . '" given.'
        );

        $link = new DocumentLink($input, $this->manager, $this->parent);
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

        $link = new DocumentLink($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * self: the link that generated the current response document.
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

        $link = new DocumentLink($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * related: a related resource link when the primary data represents a resource relationship.
     * If present, a related resource link MUST reference a valid URL
     *
     * The following related link includes a URL as well as meta-information about a related resource collection:
     *
     * "links": {
     *   "related": {
     *     "href": "http://example.com/articles/1/comments",
     *     "meta": {
     *       "count": 10
     *     }
     *   }
     * }
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

        $link = new DocumentLink($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
     *
     * @param mixed $input
     */
    public function testFirstCanBeAnObjectOrStringOrNull($input): void
    {
        $object = new \stdClass();
        $object->self = 'https://example.org/self';
        $object->first = $input;

        // Input must be null or string
        if (gettype($input) === 'string') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'first'], $link->getKeys());

            $this->assertTrue($link->has('first'));
            $this->assertSame($input, $link->get('first'));

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self'], $link->getKeys());

            $this->assertFalse($link->has('first'));

            return;
        } elseif (gettype($input) === 'object') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'first'], $link->getKeys());

            $this->assertTrue($link->has('first'));
            $this->assertInstanceOf(Accessable::class, $link->get('first'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "first" has to be an object, a string or null, "' . gettype($input) . '" given.'
        );

        $link = new DocumentLink($object, $this->manager, $this->parent);
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
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame($link->getKeys(), ['self', 'last']);

            $this->assertTrue($link->has('last'));
            $this->assertSame($link->get('last'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame($link->getKeys(), ['self']);

            $this->assertFalse($link->has('last'));

            return;
        } elseif (gettype($input) === 'object') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame($link->getKeys(), ['self', 'last']);

            $this->assertTrue($link->has('last'));
            $this->assertInstanceOf(Accessable::class, $link->get('last'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "last" has to be an object, a string or null, "' . gettype($input) . '" given.'
        );

        $link = new DocumentLink($object, $this->manager, $this->parent);
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
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'prev'], $link->getKeys());

            $this->assertTrue($link->has('prev'));
            $this->assertSame($link->get('prev'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self'], $link->getKeys());

            $this->assertFalse($link->has('prev'));

            return;
        } elseif (gettype($input) === 'object') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'prev'], $link->getKeys());

            $this->assertTrue($link->has('prev'));
            $this->assertInstanceOf(Accessable::class, $link->get('prev'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "prev" has to be an object, a string or null, "' . gettype($input) . '" given.'
        );

        $link = new DocumentLink($object, $this->manager, $this->parent);
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
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'next'], $link->getKeys());

            $this->assertTrue($link->has('next'));
            $this->assertSame($link->get('next'), $input);

            return;
        } elseif (gettype($input) === 'NULL') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self'], $link->getKeys());

            $this->assertFalse($link->has('next'));

            return;
        } elseif (gettype($input) === 'object') {
            $link = new DocumentLink($object, $this->manager, $this->parent);

            $this->assertSame(['self', 'next'], $link->getKeys());

            $this->assertTrue($link->has('next'));
            $this->assertInstanceOf(Accessable::class, $link->get('next'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "next" has to be an object, a string or null, "' . gettype($input) . '" given.'
        );

        $link = new DocumentLink($object, $this->manager, $this->parent);
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';

        $link = new DocumentLink($object, $this->manager, $this->parent);

        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
