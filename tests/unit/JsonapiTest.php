<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Jsonapi;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class JsonapiTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

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

		$jsonapi = new Jsonapi($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $jsonapi);

		$this->assertTrue($jsonapi->hasVersion());
		$this->assertSame($jsonapi->getVersion(), '1.0');
		$this->assertTrue($jsonapi->hasMeta());
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $jsonapi->getMeta());
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
			$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', new Jsonapi($input));
			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$jsonapi = new Jsonapi($input);
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
			$this->setExpectedException('InvalidArgumentException');
			$jsonapi = new Jsonapi($object);
			return;
		}

		$jsonapi = new Jsonapi($object);

		// other input must be transformed to string
		$this->assertTrue($jsonapi->hasVersion());
		$this->assertTrue(is_string($jsonapi->getVersion()));
	}
}
