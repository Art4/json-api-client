<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorLinkTest extends \PHPUnit_Framework_TestCase
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
	 * @test all properties can exist
	 *
	 * An error object MAY have the following members:
	 * - links: a links object containing the following members:
	 *   - about: a link that leads to further details about this particular occurrence of the problem.
	 */
	public function testAllPropertiesExists()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';
		$object->about = 'http://example.org/about';

		$link = new ErrorLink($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('about', 'meta', 'href'));

		$this->assertTrue($link->has('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('meta'));
		$this->assertTrue($link->has('about'));
		$this->assertSame($link->get('about'), 'http://example.org/about');

		$this->assertSame($link->asArray(), array(
			'about' => $link->get('about'),
			'meta' => $link->get('meta'),
			'href' => $link->get('href'),
		));

		// TODO: Test full array

	}

	/**
	 * @test 'about' property must be set
	 *
	 * An error object MAY have the following members:
	 * - links: a links object containing the following members:
	 *   - about: a link that leads to further details about this particular occurrence of the problem.
	 */
	public function testAboutMustBeSet()
	{
		$object = new \stdClass();
		$object->foobar = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'ErrorLink MUST contain these properties: about'
		);

		$link = new ErrorLink($object, $this->manager);
	}

	/**
	 * @test 'about' property can be a link object
	 *
	 * An error object MAY have the following members:
	 * - links: a links object containing the following members:
	 *   - about: a link that leads to further details about this particular occurrence of the problem.
	 */
	public function testAboutCanBeAnObject()
	{
		$object = new \stdClass();
		$object->about = new \stdClass();

		$link = new ErrorLink($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
		$this->assertSame($link->getKeys(), array('about'));

		$this->assertTrue($link->has('about'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('about'));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of each links member MUST be an object (a "links object").
	 */
	public function testCreateWithDataprovider($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link has to be an object, "' . gettype($input) . '" given.'
		);

		$link = new ErrorLink($input, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * test create without object or string attribute throws exception
	 */
	public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'string' or gettype($input) === 'object' )
		{
			return;
		}

		$object = new \stdClass();
		$object->about = 'http://example.org/about';
		$object->input = $input;

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link = new ErrorLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of the about member MUST be an object (a "links object") or a string.
	 */
	public function testAboutWithDataproviderThrowsException($input)
	{
		// Aabout must be string or object
		if ( gettype($input) === 'string' or gettype($input) === 'object' )
		{
			return;
		}

		$object = new \stdClass;
		$object->about = $input;

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link = new ErrorLink($object, $this->manager);
	}

	/**
	 * @test
	 */
	public function testGetOnANonExistingKeyThrowsException()
	{
		$object = new \stdClass();
		$object->about = 'http://example.org/about';

		$link = new ErrorLink($object, $this->manager);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
