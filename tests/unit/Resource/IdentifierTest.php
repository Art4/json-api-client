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

use Art4\JsonApiClient\Resource\Identifier;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class IdentifierTest extends \PHPUnit_Framework_TestCase
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

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$identifier->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $identifier);
		$this->assertSame($identifier->getKeys(), array('type', 'id'));

		$this->assertSame($identifier->get('type'), 'type');
		$this->assertSame($identifier->get('id'), '789');
		$this->assertFalse($identifier->has('meta'));

		$this->assertSame($identifier->asArray(), array(
			'type' => $identifier->get('type'),
			'id' => $identifier->get('id'),
		));

		$this->assertSame($identifier->asArray(true), array(
			'type' => $identifier->get('type'),
			'id' => $identifier->get('id'),
		));
	}

	/**
	 * @test create with object and meta
	 */
	public function testCreateWithObjectAndMeta()
	{
		$object = new \stdClass();
		$object->type = 'types';
		$object->id = 159;
		$object->meta = new \stdClass();

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$identifier->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);

		$this->assertSame($identifier->get('type'), 'types');
		$this->assertSame($identifier->get('id'), '159');
		$this->assertTrue($identifier->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $identifier->get('meta'));
		$this->assertSame($identifier->getKeys(), array('type', 'id', 'meta'));
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

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$identifier->parse($object);

		$this->assertTrue(is_string($identifier->get('type')));
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

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource Id cannot be an array or object'
			);
		}

		$identifier->parse($object);

		$this->assertTrue(is_string($identifier->get('id')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Resource has to be an object, "' . gettype($input) . '" given.'
		);

		$identifier->parse($input);
	}

	/**
	 * @test A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain a type'
		);

		$identifier->parse($object);
	}

	/**
	 * @test A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain an id'
		);

		$identifier->parse($object);
	}

	/**
	 * @test get() on an undefined value throws Exception
	 */
	public function testGetWithUndefinedValueThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'posts';
		$object->id = 9;

		$identifier = new Identifier($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$identifier->parse($object);

		$this->assertFalse($identifier->has('foobar'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"foobar" doesn\'t exist in this identifier.'
		);

		$identifier->get('foobar');
	}
}
