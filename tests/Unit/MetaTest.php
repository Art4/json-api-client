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

use Art4\JsonApiClient\Meta;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class MetaTest extends \PHPUnit_Framework_TestCase
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
		$object->object = new \stdClass();
		$object->array = array();
		$object->string = 'string';
		$object->number_int = 654;
		$object->number_float = 654.321;
		$object->true = true;
		$object->false = false;
		$object->null = null;

		$meta = new Meta($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$meta->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $meta);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $meta);
		$this->assertTrue($meta->has('object'));
		$this->assertTrue(is_object($meta->get('object')));
		$this->assertTrue($meta->has('array'));
		$this->assertTrue(is_array($meta->get('array')));
		$this->assertTrue($meta->has('string'));
		$this->assertTrue(is_string($meta->get('string')));
		$this->assertTrue($meta->has('number_int'));
		$this->assertTrue(is_int($meta->get('number_int')));
		$this->assertTrue($meta->has('number_float'));
		$this->assertTrue(is_float($meta->get('number_float')));
		$this->assertTrue($meta->has('true'));
		$this->assertTrue($meta->get('true'));
		$this->assertTrue($meta->has('false'));
		$this->assertFalse($meta->get('false'));
		$this->assertTrue($meta->has('null'));
		$this->assertNull($meta->get('null'));

		$this->assertSame($meta->getKeys(), array('object', 'array', 'string', 'number_int', 'number_float', 'true', 'false', 'null'));

		$this->assertSame($meta->asArray(), array(
			'object' => $meta->get('object'),
			'array' => array(),
			'string' => 'string',
			'number_int' => 654,
			'number_float' => 654.321,
			'true' => true,
			'false' => false,
			'null' => null,
		));

		// Test full array
		$this->assertSame($meta->asArray(true), array(
			'object' => (array) $meta->get('object'),
			'array' => array(),
			'string' => 'string',
			'number_int' => 654,
			'number_float' => 654.321,
			'true' => true,
			'false' => false,
			'null' => null,
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of each meta member MUST be an object (a "meta object").
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		$meta = new Meta($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Meta', $meta->parse($input));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Meta has to be an object, "' . gettype($input) . '" given.'
		);

		$meta->parse($input);
	}

	/**
	 * @test get() with not existing key throws an exception
	 */
	public function testGetWithNotExistingKeyThrowsException()
	{
		$object = new \stdClass();

		$meta = new Meta($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$meta->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $meta);

		$this->assertFalse($meta->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$meta->get('something');
	}
}
