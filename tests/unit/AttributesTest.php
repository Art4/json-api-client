<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Attributes;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class AttributesTest extends \PHPUnit_Framework_TestCase
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

		$attributes = new Attributes($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $attributes);
		$this->assertTrue($attributes->has('object'));
		$this->assertTrue(is_object($attributes->get('object')));
		$this->assertTrue($attributes->has('array'));
		$this->assertTrue(is_array($attributes->get('array')));
		$this->assertTrue($attributes->has('string'));
		$this->assertTrue(is_string($attributes->get('string')));
		$this->assertTrue($attributes->has('number_int'));
		$this->assertTrue(is_int($attributes->get('number_int')));
		$this->assertTrue($attributes->has('number_float'));
		$this->assertTrue(is_float($attributes->get('number_float')));
		$this->assertTrue($attributes->has('true'));
		$this->assertTrue($attributes->get('true'));
		$this->assertTrue($attributes->has('false'));
		$this->assertFalse($attributes->get('false'));
		$this->assertTrue($attributes->has('null'));
		$this->assertNull($attributes->get('null'));
		$this->assertSame($attributes->getKeys(), array('object', 'array', 'string', 'number_int', 'number_float', 'true', 'false', 'null'));

		$this->assertSame($attributes->asArray(), array(
			'object' => $attributes->get('object'),
			'array' => $attributes->get('array'),
			'string' => $attributes->get('string'),
			'number_int' => $attributes->get('number_int'),
			'number_float' => $attributes->get('number_float'),
			'true' => $attributes->get('true'),
			'false' => $attributes->get('false'),
			'null' => $attributes->get('null'),
		));

		// Test full array
		$this->assertSame($attributes->asArray(true), array(
			'object' => (array) $attributes->get('object'),
			'array' => $attributes->get('array'),
			'string' => $attributes->get('string'),
			'number_int' => $attributes->get('number_int'),
			'number_float' => $attributes->get('number_float'),
			'true' => $attributes->get('true'),
			'false' => $attributes->get('false'),
			'null' => $attributes->get('null'),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithDataProvider($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Attributes', new Attributes($input, $this->manager));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Attributes has to be an object, "' . gettype($input) . '" given.'
		);

		$error = new Attributes($input, $this->manager);
	}

	/**
	 * @test
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'posts';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
		);

		$attributes = new Attributes($object, $this->manager);
	}

	/**
	 * @test
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->id = '5';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
		);

		$attributes = new Attributes($object, $this->manager);
	}

	/**
	 * @test
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithRelationshipsPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->relationships = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
		);

		$attributes = new Attributes($object, $this->manager);
	}

	/**
	 * @test
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithLinksPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->links = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
		);

		$attributes = new Attributes($object, $this->manager);
	}
}
