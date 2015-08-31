<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Jsonapi;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class JsonapiTest extends \PHPUnit_Framework_TestCase
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

		$jsonapi = new Jsonapi($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $jsonapi);
		$this->assertSame($jsonapi->getKeys(), array('version', 'meta'));

		$this->assertFalse($jsonapi->has('testobj'));
		$this->assertFalse($jsonapi->has('teststring'));
		$this->assertTrue($jsonapi->has('version'));
		$this->assertSame($jsonapi->get('version'), '1.0');
		$this->assertTrue($jsonapi->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $jsonapi->getMeta());

		$this->assertSame($jsonapi->asArray(), array(
			'version' => $jsonapi->get('version'),
			'meta' => $jsonapi->get('meta'),
		));

		// Test full array
		$this->assertSame($jsonapi->asArray(true), array(
			'version' => $jsonapi->get('version'),
			'meta' => $jsonapi->get('meta')->asArray(true),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * If present, the value of the jsonapi member MUST be an object (a "jsonapi object").
	 */
	public function testCreateWithDataprovider($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', new Jsonapi($input, $this->manager));
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$jsonapi = new Jsonapi($input, $this->manager);
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

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
			$jsonapi = new Jsonapi($object, $this->manager);
			return;
		}

		$jsonapi = new Jsonapi($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi);
		$this->assertSame($jsonapi->getKeys(), array('version'));

		// other input must be transformed to string
		$this->assertTrue($jsonapi->has('version'));
		$this->assertTrue(is_string($jsonapi->get('version')));
	}
}
