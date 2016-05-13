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

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Item;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ItemTest extends \PHPUnit_Framework_TestCase
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

		$resource = new Item($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $resource);
		$this->assertSame($resource->getKeys(), array('type', 'id'));

		$this->assertSame($resource->get('type'), 'type');
		$this->assertSame($resource->get('id'), '789');
		$this->assertFalse($resource->has('meta'));
		$this->assertFalse($resource->has('attributes'));
		$this->assertFalse($resource->has('relationships'));
		$this->assertFalse($resource->has('links'));
		$this->assertFalse($resource->isNull());
		$this->assertFalse($resource->isIdentifier());
		$this->assertTrue($resource->isItem());
		$this->assertFalse($resource->isCollection());

		// test get() with not existing key throws an exception
		$this->assertFalse($resource->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this resource.'
		);

		$resource->get('something');
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

		$resource = new Item($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);

		$this->assertSame($resource->get('type'), 'type');
		$this->assertSame($resource->get('id'), '789');
		$this->assertTrue($resource->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $resource->get('meta'));
		$this->assertTrue($resource->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\AttributesInterface', $resource->get('attributes'));
		$this->assertTrue($resource->has('relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollectionInterface', $resource->get('relationships'));
		$this->assertTrue($resource->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ItemLinkInterface', $resource->get('links'));
		$this->assertSame($resource->getKeys(), array('type', 'id', 'meta', 'attributes', 'relationships', 'links'));

		$this->assertSame($resource->asArray(), array(
			'type' => $resource->get('type'),
			'id' => $resource->get('id'),
			'meta' => $resource->get('meta'),
			'attributes' => $resource->get('attributes'),
			'relationships' => $resource->get('relationships'),
			'links' => $resource->get('links'),
		));

		// Test full array
		$this->assertSame($resource->asArray(true), array(
			'type' => $resource->get('type'),
			'id' => $resource->get('id'),
			'meta' => $resource->get('meta')->asArray(true),
			'attributes' => $resource->get('attributes')->asArray(true),
			'relationships' => $resource->get('relationships')->asArray(true),
			'links' => $resource->get('links')->asArray(true),
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

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource type cannot be an array or object'
			);
		}

		$item = new Item($object, $this->manager);

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

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource id cannot be an array or object'
			);
		}

		$item = new Item($object, $this->manager);

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
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Resource has to be an object, "' . gettype($input) . '" given.'
		);

		$item = new Item($input, $this->manager);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain a type'
		);

		$item = new Item($object, $this->manager);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain an id'
		);

		$item = new Item($object, $this->manager);
	}
}
