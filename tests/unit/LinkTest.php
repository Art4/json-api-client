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

		$link = new Link($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('href', 'linkobj', 'link', 'meta'));

		$this->assertTrue($link->has('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $link->get('meta'));
		$this->assertTrue($link->has('link'));
		$this->assertSame($link->get('link'), 'http://example.org/link');
		$this->assertTrue($link->has('linkobj'));
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link->get('linkobj'));

		$this->assertSame($link->asArray(), array(
			'href' => $link->get('href'),
			'linkobj' => $link->get('linkobj'),
			'link' => $link->get('link'),
			'meta' => $link->get('meta'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'href' => $link->get('href'),
			'linkobj' => $link->get('linkobj')->asArray(true),
			'link' => $link->get('link'),
			'meta' => $link->get('meta')->asArray(true),
		));
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
			$link = new Link($object, $this->manager);

			$this->assertTrue(is_string($link->get('href')));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$link = new Link($object, $this->manager);
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
			$this->assertInstanceOf('Art4\JsonApiClient\Link', new Link($input, $this->manager));
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$link = new Link($input, $this->manager);
	}
}
