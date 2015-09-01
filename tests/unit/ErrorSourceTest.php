<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorSource;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorSourceTest extends \PHPUnit_Framework_TestCase
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

		$source = new ErrorSource($object, $this->manager);

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

		// Test full array
		$this->assertSame($source->asArray(true), array(
			'pointer' => $source->get('pointer'),
			'parameter' => $source->get('parameter'),
		));

		// test get() with not existing key throws an exception
		$this->assertFalse($source->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this error source.'
		);

		$source->get('something');
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * source: an object containing references to ...
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'ErrorSource has to be an object, "' . gettype($input) . '" given.'
		);

		$source = new ErrorSource($input, $this->manager);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "pointer" has to be a string, "' . gettype($input) . '" given.'
		);

		$source = new ErrorSource($object, $this->manager);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "parameter" has to be a string, "' . gettype($input) . '" given.'
		);

		$source = new ErrorSource($object, $this->manager);
	}
}
