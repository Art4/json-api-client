<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\PaginationLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class PaginationLinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

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
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('last', 'next'));

		$this->assertFalse($link->has('about'));
		$this->assertFalse($link->has('first'));
		$this->assertTrue($link->has('last'));
		$this->assertSame($link->get('last'), 'http://example.org/last');
		$this->assertFalse($link->has('prev'));
		$this->assertTrue($link->has('next'));
		$this->assertSame($link->get('next'), 'http://example.org/next');

		$this->assertSame($link->asArray(), array(
			'last' => $link->get('last'),
			'next' => $link->get('next'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'last' => $link->get('last'),
			'next' => $link->get('next'),
		));
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

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

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
			$this->assertSame($link->getKeys(), array('first'));

			$this->assertTrue($link->has('first'));
			$this->assertTrue(is_string($link->get('first')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);
			$this->assertSame($link->getKeys(), array());

			$this->assertFalse($link->has('first'));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

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
			$this->assertSame($link->getKeys(), array('last'));

			$this->assertTrue($link->has('last'));
			$this->assertTrue(is_string($link->get('last')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);
			$this->assertSame($link->getKeys(), array());

			$this->assertFalse($link->has('last'));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

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
			$this->assertSame($link->getKeys(), array('prev'));

			$this->assertTrue($link->has('prev'));
			$this->assertTrue(is_string($link->get('prev')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);
			$this->assertSame($link->getKeys(), array());

			$this->assertFalse($link->has('prev'));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

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
			$this->assertSame($link->getKeys(), array('next'));

			$this->assertTrue($link->has('next'));
			$this->assertTrue(is_string($link->get('next')));

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new PaginationLink($object);
			$this->assertSame($link->getKeys(), array());

			$this->assertFalse($link->has('next'));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$link = new PaginationLink($object);
	}
}
