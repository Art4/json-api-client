<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorSource;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class ErrorSourceTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test only 'about' property' can exist
	 *
	 * source: an object containing references to the source of the error, optionally including any of the following members:
	 * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
	 * - parameter: a string indicating which query parameter caused the error.
	 */
	public function testOnlyPointerParameterPropertiesExists()
	{
		$object = new \stdClass();
		$object->pointer = '/pointer';
		$object->parameter = 'parameter';
		$object->ignore = 'must be ignored';

		$source = new ErrorSource($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSource', $source);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $source);
		$this->assertSame($source->getKeys(), array('pointer', 'parameter'));

		$this->assertFalse($source->has('ignore'));
		$this->assertTrue($source->has('pointer'));
		$this->assertSame($source->get('pointer'), '/pointer');
		$this->assertTrue($source->has('parameter'));
		$this->assertSame($source->get('parameter'), 'parameter');

		$this->assertSame($source->asArray(), array(
			'pointer' => $source->get('pointer'),
			'parameter' => $source->get('parameter'),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
	 */
	public function testPointerMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->pointer = $input;

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$source = new ErrorSource($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * parameter: a string indicating which query parameter caused the error.
	 */
	public function testParameterMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->parameter = $input;

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$source = new ErrorSource($object);
	}
}
