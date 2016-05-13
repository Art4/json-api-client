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

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Link;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class LinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();

		// Mock parent link
		$this->parent_link = $this->getMockBuilder('Art4\JsonApiClient\LinkInterface')
			->getMock();
	}

	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';
		$object->link = 'http://example.org/link';

		$link = new Link($object, $this->manager, $this->parent_link);

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);

		$this->assertTrue($link->has('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $link->get('meta'));
		$this->assertTrue($link->has('link'));
		$this->assertSame($link->get('link'), 'http://example.org/link');

		$this->assertSame($link->asArray(), array(
			'meta' => $link->get('meta'),
			'href' => $link->get('href'),
			'link' => $link->get('link'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'meta' => $link->get('meta')->asArray(true),
			'href' => $link->get('href'),
			'link' => $link->get('link'),
		));

		// test get() with not existing key throws an exception
		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * - an object ("link object") which can contain the following members:
	 *   - href: a string containing the link's URL.
	 */
	public function testHrefHasToBeAString($input)
	{
		$object = new \stdClass();
		$object->href = $input;

		if ( gettype($input) === 'string' )
		{
			$link = new Link($object, $this->manager, $this->parent_link);

			$this->assertTrue(is_string($link->get('href')));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Every link attribute has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new Link($object, $this->manager, $this->parent_link);
	}

	/**
	 * @test href attribute must be set
	 *
	 * - an object ("link object") which can contain the following members:
	 *   - href: a string containing the link's URL.
	 */
	public function testHrefAttributeMustBeSet()
	{
		$object = new \stdClass();
		$object->related = 'http://example.org/related';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link must have a "href" attribute.'
		);

		$link = new Link($object, $this->manager, $this->parent_link);
	}

	/**
	 * @test meta attribute will be parsed as Meta object inside Link
	 */
	public function testMetaIsParsedAsObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';

		$link = new Link($object, $this->manager, $this->parent_link);

		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $link->get('meta'));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of each links member MUST be an object (a "links object").
	 */
	public function testCreateWithDataprovider($input)
	{
		// A link object must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link = new Link($input, $this->manager, $this->parent_link);
	}
}
