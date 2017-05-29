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

use Art4\JsonApiClient\ResourceItem;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ResourceItemTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
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
		$object->type = 'type';
		$object->id = 789;

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$item->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceItem', $item);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $item);
		$this->assertSame($item->getKeys(), array('type', 'id'));

		$this->assertSame($item->get('type'), 'type');
		$this->assertSame($item->get('id'), '789');
		$this->assertFalse($item->has('meta'));
		$this->assertFalse($item->has('attributes'));
		$this->assertFalse($item->has('relationships'));
		$this->assertFalse($item->has('links'));

		// test get() with not existing key throws an exception
		$this->assertFalse($item->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
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

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$item->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceItem', $item);

		$this->assertSame($item->get('type'), 'type');
		$this->assertSame($item->get('id'), '789');
		$this->assertTrue($item->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $item->get('meta'));
		$this->assertTrue($item->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\AttributesInterface', $item->get('attributes'));
		$this->assertTrue($item->has('relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollectionInterface', $item->get('relationships'));
		$this->assertTrue($item->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\ResourceItemLinkInterface', $item->get('links'));
		$this->assertSame($item->getKeys(), array('type', 'id', 'meta', 'attributes', 'relationships', 'links'));

		$this->assertSame($item->asArray(), array(
			'type' => $item->get('type'),
			'id' => $item->get('id'),
			'meta' => $item->get('meta'),
			'attributes' => $item->get('attributes'),
			'relationships' => $item->get('relationships'),
			'links' => $item->get('links'),
		));

		// Test full array
		$this->assertSame($item->asArray(true), array(
			'type' => $item->get('type'),
			'id' => $item->get('id'),
			'meta' => $item->get('meta')->asArray(true),
			'attributes' => $item->get('attributes')->asArray(true),
			'relationships' => $item->get('relationships')->asArray(true),
			'links' => $item->get('links')->asArray(true),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
		*
	 * The values of the id and type members MUST be strings.
	 */
	public function testTypeCannotBeAnObjectOrArray($input)
	{
		$object = new \stdClass();
		$object->type = $input;
		$object->id = '753';

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource type cannot be an array or object'
			);
		}

		$item->parse($object);

		$this->assertTrue(is_string($item->get('type')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The values of the id and type members MUST be strings.
	 */
	public function testIdCannotBeAnObjectOrArray($input)
	{
		$object = new \stdClass();
		$object->type = 'posts';
		$object->id = $input;

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource id cannot be an array or object'
			);
		}

		$item->parse($object);

		$this->assertTrue(is_string($item->get('id')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A "resource object" is an object that identifies an individual resource.
	 * A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\ResourceItem', $item);

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Resource has to be an object, "' . gettype($input) . '" given.'
		);

		$item->parse($input);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain a type'
		);

		$item->parse($object);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$item = new ResourceItem($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain an id'
		);

		$item->parse($object);
	}
}
