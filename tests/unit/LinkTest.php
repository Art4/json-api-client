<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Link;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class LinkTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

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

		$link = new Link($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link);

		$this->assertTrue($link->__isset('href'));
		$this->assertSame($link->get('href'), 'http://example.org/href');
		$this->assertTrue($link->hasMeta());
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $link->get('meta'));
		$this->assertTrue($link->__isset('link'));
		$this->assertTrue($link->__isset('linkobj'));
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link->get('linkobj'));
		$this->assertSame($link->get('link'), 'http://example.org/link');
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
			$link = new Link($object);

			$this->assertTrue(is_string($link->get('href')));

			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new Link($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMetaHasToBeAnObject()
	{
		$object = new \stdClass();
		$object->meta = 'http://example.org';

		$link = new Link($object);
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
			$this->assertInstanceOf('Art4\JsonApiClient\Link', new Link($input));
			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new Link($input);
	}
}