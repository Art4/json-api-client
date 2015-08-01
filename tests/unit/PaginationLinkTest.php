<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\PaginationLink;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class PaginationLinkTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test The following keys MUST be used for pagination links:
	 *
	 * first: the first page of data
	 * last: the last page of data
	 * prev: the previous page of data
	 * next: the next page of data
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testOnlyPaginationPropertiesExists()
	{
		$object = new \stdClass();
		$object->first = null;
		$object->last = 'http://example.org/last';
		$object->prev = null;
		$object->next = 'http://example.org/next';
		$object->about = 'http://example.org/about';

		$link = new PaginationLink($object);

		$this->assertInstanceOf('Art4\JsonApiClient\PaginationLink', $link);

		$this->assertFalse($link->__isset('about'));
		$this->assertFalse($link->__isset('first'));
		$this->assertTrue($link->__isset('last'));
		$this->assertSame($link->get('last'), 'http://example.org/last');
		$this->assertFalse($link->__isset('prev'));
		$this->assertTrue($link->__isset('next'));
		$this->assertSame($link->get('next'), 'http://example.org/next');
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

		$this->setExpectedException('InvalidArgumentException');

		$link = new PaginationLink($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testFirstCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->first = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new PaginationLink($object);

			$this->assertTrue($link->__isset('first'));
			$this->assertTrue(is_string($link->get('first')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);

			$this->assertFalse($link->__isset('first'));

			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new PaginationLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testLastCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->last = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new PaginationLink($object);

			$this->assertTrue($link->__isset('last'));
			$this->assertTrue(is_string($link->get('last')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);

			$this->assertFalse($link->__isset('last'));

			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new PaginationLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testPrevCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->prev = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new PaginationLink($object);

			$this->assertTrue($link->__isset('prev'));
			$this->assertTrue(is_string($link->get('prev')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);

			$this->assertFalse($link->__isset('prev'));

			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new PaginationLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testNextCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->next = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new PaginationLink($object);

			$this->assertTrue($link->__isset('next'));
			$this->assertTrue(is_string($link->get('next')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);

			$this->assertFalse($link->__isset('next'));

			return;
		}

		$this->setExpectedException('InvalidArgumentException');

		$link = new PaginationLink($object);
	}
}
