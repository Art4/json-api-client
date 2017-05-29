<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

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

use Art4\JsonApiClient\DocumentLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class DocumentLinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();

		// Mock parent
		$this->parent = $this->getMockBuilder('Art4\JsonApiClient\AccessInterface')
			->getMock();

		$this->parent->expects($this->any())
			->method('has')
			->with('data')
			->will($this->returnValue(true));
	}

	/**
	 * @test only 'about' property' can exist
	 *
	 * The top-level links object MAY contain the following members:
	 * - self: the link that generated the current response document.
	 * - related: a related resource link when the primary data represents a resource relationship.
	 * - pagination links for the primary data.
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

		$link = new DocumentLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'related', 'first', 'last', 'prev', 'next', 'custom', 'meta'));

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

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
			'custom' => $link->get('custom'),
			'meta' => $link->get('meta'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
			'custom' => $link->get('custom'),
			'meta' => $link->get('meta'),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * links: a links object related to the primary data.
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$link = new DocumentLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'DocumentLink has to be an object, "' . gettype($input) . '" given.'
		);

		$link->parse($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * test create without object or string attribute throws exception
	 */
	public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'string' or gettype($input) === 'object' )
		{
			return;
		}

		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->input = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * self: the link that generated the current response document.
	 */
	public function testSelfMustBeAStringOrObject($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' or gettype($input) === 'object' )
		{
			return;
		}

		$object = new \stdClass();
		$object->self = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "self" has to be a string or object, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
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
	 */
	public function testRelatedMustBeAStringOrObject($input)
	{
		$object = new \stdClass();
		$object->related = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		// Input must be a string or object
		if ( gettype($input) === 'string' or gettype($input) === 'object' )
		{
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
	 */
	public function testFirstCanBeAnObjectOrStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->first = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'first'));

			$this->assertTrue($link->has('first'));
			$this->assertSame($link->get('first'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('first'));

			return;
		}
		elseif ( gettype($input) === 'object' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'first'));

			$this->assertTrue($link->has('first'));
			$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('first'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "first" has to be an object, a string or null, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testLastCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->last = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'last'));

			$this->assertTrue($link->has('last'));
			$this->assertSame($link->get('last'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('last'));

			return;
		}
		elseif ( gettype($input) === 'object' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'last'));

			$this->assertTrue($link->has('last'));
			$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('last'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "last" has to be an object, a string or null, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testPrevCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->prev = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'prev'));

			$this->assertTrue($link->has('prev'));
			$this->assertSame($link->get('prev'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('prev'));

			return;
		}
		elseif ( gettype($input) === 'object' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'prev'));

			$this->assertTrue($link->has('prev'));
			$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('prev'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "prev" has to be an object, a string or null, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testNextCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->next = $input;

		$link = new DocumentLink($this->manager, $this->parent);

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'next'));

			$this->assertTrue($link->has('next'));
			$this->assertSame($link->get('next'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('next'));

			return;
		}
		elseif ( gettype($input) === 'object' )
		{
			$link->parse($object);

			$this->assertSame($link->getKeys(), array('self', 'next'));

			$this->assertTrue($link->has('next'));
			$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('next'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "next" has to be an object, a string or null, "' . gettype($input) . '" given.'
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

		$link = new DocumentLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
