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
		$object->linkobj = new \stdClass();
		$object->link = 'http://example.org/link';

		$link = new Link($object, $this->manager, $this->parent_link);

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('meta', 'href', 'linkobj', 'link'));

		$this->assertTrue($link->has('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $link->get('meta'));
		$this->assertTrue($link->has('link'));
		$this->assertSame($link->get('link'), 'http://example.org/link');
		$this->assertTrue($link->has('linkobj'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('linkobj'));

		$this->assertSame($link->asArray(), array(
			'meta' => $link->get('meta'),
			'href' => $link->get('href'),
			'linkobj' => $link->get('linkobj'),
			'link' => $link->get('link'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'meta' => $link->get('meta')->asArray(true),
			'href' => $link->get('href'),
			'linkobj' => $link->get('linkobj')->asArray(true),
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

		if ( gettype($input) === 'string' )
		{
			$link = new Link($object, $this->manager, $this->parent_link);

			$this->assertTrue(is_string($link->get('href')));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$link = new Link($object, $this->manager, $this->parent_link);
	}

	/**
	 * @test meta attribute will be parsed as Link object inside Resource\Item
	 */
	public function testMetaIsParsedAsLinkInsideItem()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		// Mock parent link
		$this->parent_link = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$link = new Link($object, $this->manager, $this->parent_link);

		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('meta'));
	}

	/**
	 * @test meta attribute will be parsed as Meta object inside Link
	 */
	public function testMetaIsParsedAsMetaInsideItem()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		// Mock parent link
		$this->parent_link = $this->getMockBuilder('Art4\JsonApiClient\LinkInterface')
			->getMock();

		$link = new Link($object, $this->manager, $this->parent_link);

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
		// A link object could be empty
		if ( gettype($input) === 'object' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', new Link($input, $this->manager, $this->parent_link));
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link has to be an object, "' . gettype($input) . '" given.'
		);

		$link = new Link($input, $this->manager, $this->parent_link);
	}
}
