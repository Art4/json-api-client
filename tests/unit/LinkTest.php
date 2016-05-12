<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Link;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class LinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();

		// Mock parent link
		$this->parent_link = $this->getMockBuilder('Art4\JsonApiClient\LinkInterface')
			->getMock();
	}

	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';
		$object->link = 'http://example.org/link';

		$link = new Link($this->manager, $this->parent_link);
		$link->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);

		$this->assertTrue($link->has('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $link->get('meta'));
		$this->assertTrue($link->has('link'));
		$this->assertSame($link->get('link'), 'http://example.org/link');

		$this->assertSame($link->asArray(), array(
			'meta' => $link->get('meta'),
			'href' => $link->get('href'),
			'link' => $link->get('link'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'meta' => $link->get('meta')->asArray(true),
			'href' => $link->get('href'),
			'link' => $link->get('link'),
		));

		// test get() with not existing key throws an exception
		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * - an object ("link object") which can contain the following members:
	 *   - href: a string containing the link's URL.
	 */
	public function testHrefHasToBeAString($input)
	{
		$object = new \stdClass();
		$object->href = $input;

		$link = new Link($this->manager, $this->parent_link);

		if ( gettype($input) === 'string' )
		{
			$link->parse($object);

			$this->assertTrue(is_string($link->get('href')));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Every link attribute has to be a string, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @test href attribute must be set
	 *
	 * - an object ("link object") which can contain the following members:
	 *   - href: a string containing the link's URL.
	 */
	public function testHrefAttributeMustBeSet()
	{
		$object = new \stdClass();
		$object->related = 'http://example.org/related';

		$link = new Link($this->manager, $this->parent_link);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link must have a "href" attribute.'
		);

		$link->parse($object);
	}

	/**
	 * @test meta attribute will be parsed as Meta object inside Link
	 */
	public function testMetaIsParsedAsObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';

		$link = new Link($this->manager, $this->parent_link);
		$link->parse($object);

		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $link->get('meta'));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of each links member MUST be an object (a "links object").
	 */
	public function testCreateWithDataprovider($input)
	{
		// A link object must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$link = new Link($this->manager, $this->parent_link);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link->parse($input);
	}
}
