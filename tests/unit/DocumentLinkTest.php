<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\DocumentLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class DocumentLinkTest extends \PHPUnit_Framework_TestCase
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
	 * The top-level links object MAY contain the following members:
	 * - self: the link that generated the current response document.
	 * - related: a related resource link when the primary data represents a resource relationship.
	 * - pagination links for the primary data.
	 */
	public function testOnlySelfRelatedPaginationPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->related = 'http://example.org/related';
		$object->first = 'http://example.org/first';
		$object->last = 'http://example.org/last';
		$object->prev = 'http://example.org/prev';
		$object->next = 'http://example.org/next';
		$object->custom = 'http://example.org/custom';

		$link = new DocumentLink($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'related', 'first', 'last', 'prev', 'next', 'custom'));

		$this->assertTrue($link->has('custom'));
		$this->assertSame($link->get('custom'), 'http://example.org/custom');
		$this->assertTrue($link->has('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->has('related'));
		$this->assertSame($link->get('related'), 'http://example.org/related');
		$this->assertTrue($link->has('first'));
		$this->assertSame($link->get('first'), 'http://example.org/first');
		$this->assertTrue($link->has('last'));
		$this->assertSame($link->get('last'), 'http://example.org/last');
		$this->assertTrue($link->has('prev'));
		$this->assertSame($link->get('prev'), 'http://example.org/prev');
		$this->assertTrue($link->has('next'));
		$this->assertSame($link->get('next'), 'http://example.org/next');

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
			'custom' => $link->get('custom'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
			'custom' => $link->get('custom'),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * links: a links object related to the primary data.
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
			'DocumentLink has to be an object, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($input, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * self: the link that generated the current response document.
	 */
	public function testSelfMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->self = $input;

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "self" has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * related: a related resource link when the primary data represents a resource relationship.
	 * If present, a related resource link MUST reference a valid URL
	 */
	public function testRelatedMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->related = $input;

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "related" has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testFirstCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->first = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self', 'first'));

			$this->assertTrue($link->has('first'));
			$this->assertSame($link->get('first'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('first'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "first" has to be a string or null, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testLastCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->last = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self', 'last'));

			$this->assertTrue($link->has('last'));
			$this->assertSame($link->get('last'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('last'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "last" has to be a string or null, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testPrevCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->prev = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self', 'prev'));

			$this->assertTrue($link->has('prev'));
			$this->assertSame($link->get('prev'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('prev'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "prev" has to be a string or null, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
	 */
	public function testNextCanBeAStringOrNull($input)
	{
		$object = new \stdClass();
		$object->self = 'https://example.org/self';
		$object->next = $input;

		// Input must be null or string
		if ( gettype($input) === 'string' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self', 'next'));

			$this->assertTrue($link->has('next'));
			$this->assertSame($link->get('next'), $input);

			return;
		}
		elseif ( gettype($input) === 'NULL' )
		{
			$link = new DocumentLink($object, $this->manager);
			$this->assertSame($link->getKeys(), array('self'));

			$this->assertFalse($link->has('next'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "next" has to be a string or null, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}

	/**
	 * @test
	 */
	public function testGetOnANonExistingKeyThrowsException()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';

		$link = new DocumentLink($object, $this->manager);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
