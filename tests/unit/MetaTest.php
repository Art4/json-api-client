<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Meta;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class MetaTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

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

		$meta = new Meta($object);

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
	public function testSelfMustBeAString($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Meta', new Meta($input));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$meta = new Meta($input);
	}
}
