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

use Art4\JsonApiClient\ResourceIdentifierCollection;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ResourceIdentifierCollectionTest extends \PHPUnit_Framework_TestCase
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
	 * @test create with empty array
	 */
	public function testCreateWithEmptyArray()
	{
		$collection = new ResourceIdentifierCollection($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$collection->parse(array());

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollectionInterface', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);

		$this->assertTrue(count($collection->asArray()) === 0);
		$this->assertSame($collection->getKeys(), array());
		$this->assertFalse($collection->has(0));
	}

	/**
	 * @test create with identifier object
	 */
	public function testCreateWithIdentifier()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->meta = new \stdClass();

		$collection = new ResourceIdentifierCollection($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$collection->parse(array($object, $object, $object));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollectionInterface', $collection);

		$this->assertTrue( count($collection->asArray()) === 3);
		$this->assertSame($collection->getKeys(), array(0, 1, 2));

		$this->assertTrue($collection->has(0));
		$this->assertTrue($collection->has(1));
		$this->assertTrue($collection->has(2));

		$this->assertSame($collection->asArray(), array(
			$collection->get(0),
			$collection->get(1),
			$collection->get(2),
		));

		$this->assertSame($collection->asArray(true), array(
			$collection->get(0)->asArray(true),
			$collection->get(1)->asArray(true),
			$collection->get(2)->asArray(true),
		));
	}

	/**
	 * @test create with item object
	 */
	public function testCreateWithItem()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->attributes = new \stdClass();
		$object->relationships = new \stdClass();
		$object->links = new \stdClass();

		$collection = new ResourceIdentifierCollection($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$collection->parse(array($object));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollectionInterface', $collection);

		$this->assertCount(1, $collection->asArray());
		$this->assertSame($collection->getKeys(), array(0));
		$this->assertTrue($collection->has(0));

		$resource = $collection->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierInterface', $resource);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutArrayThrowsException($input)
	{
		$collection = new ResourceIdentifierCollection($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		// Input must be an array
		if ( gettype($input) === 'array' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollection', $collection->parse($input));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Resources for a collection has to be in an array, "' . gettype($input) . '" given.'
		);

		$collection->parse($input);
	}

	/**
	 * @test get('resources') on an empty identifier collection throws an exception
	 */
	public function testGetResourcesWithEmptyCollectionThrowsException()
	{
		$collection = new ResourceIdentifierCollection($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$collection->parse(array());

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierCollection', $collection);

		$this->assertFalse($collection->has(0));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"0" doesn\'t exist in this resource.'
		);

		$collection->get(0);
	}
}
