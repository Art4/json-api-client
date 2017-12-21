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

use Art4\JsonApiClient\Jsonapi;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class JsonapiTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
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
		$object->version = '1.0';

		// This object MAY also contain a meta member, whose value is a meta object
		$object->meta = new \stdClass();

		// these properties must be ignored
		$object->testobj = new \stdClass();
		$object->teststring = 'http://example.org/link';

		$jsonapi = new Jsonapi($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
		$jsonapi->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $jsonapi);
		$this->assertSame($jsonapi->getKeys(), array('version', 'meta'));

		$this->assertFalse($jsonapi->has('testobj'));
		$this->assertFalse($jsonapi->has('teststring'));
		$this->assertTrue($jsonapi->has('version'));
		$this->assertSame($jsonapi->get('version'), '1.0');
		$this->assertTrue($jsonapi->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $jsonapi->get('meta'));

		$this->assertSame($jsonapi->asArray(), array(
			'version' => $jsonapi->get('version'),
			'meta' => $jsonapi->get('meta'),
		));

		// Test full array
		$this->assertSame($jsonapi->asArray(true), array(
			'version' => $jsonapi->get('version'),
			'meta' => $jsonapi->get('meta')->asArray(true),
		));

		// test get() with not existing key throws an exception
		$this->assertFalse($jsonapi->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this jsonapi object.'
		);

		$jsonapi->get('something');
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * If present, the value of the jsonapi member MUST be an object (a "jsonapi object").
	 */
	public function testCreateWithDataprovider($input)
	{
		$jsonapi = new Jsonapi($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi->parse($input));
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Jsonapi has to be an object, "' . gettype($input) . '" given.'
		);

		$jsonapi->parse($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The jsonapi object MAY contain a version member whose value is a string
	 */
	public function testVersionCannotBeAnObjectOrArray($input)
	{
		$object = new \stdClass();
		$object->version = $input;

		$jsonapi = new Jsonapi($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'property "version" cannot be an object or array, "' . gettype($input) . '" given.'
			);

			$jsonapi->parse($object);

			return;
		}

		$jsonapi->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi);
		$this->assertSame($jsonapi->getKeys(), array('version'));

		// other input must be transformed to string
		$this->assertTrue($jsonapi->has('version'));
		$this->assertTrue(is_string($jsonapi->get('version')));
	}
}
