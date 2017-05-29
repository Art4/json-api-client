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

use Art4\JsonApiClient\ResourceItemLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ResourceItemLinkTest extends \PHPUnit_Framework_TestCase
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
	}

	/**
	 * @test parsing of all properties
	 */
	public function testParsingPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->custom = 'http://example.org/custom';
		$object->related = new \stdClass();

		$link = new ResourceItemLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceItemLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'custom', 'related'));

		$this->assertTrue($link->has('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->has('custom'));
		$this->assertSame($link->get('custom'), 'http://example.org/custom');
		$this->assertTrue($link->has('related'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('related'));

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'custom' => $link->get('custom'),
			'related' => $link->get('related'),
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

		$link = new ResourceItemLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'ItemLink has to be an object, "' . gettype($input) . '" given.'
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
		$object->input = $input;

		$link = new ResourceItemLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
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

		$link = new ResourceItemLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
