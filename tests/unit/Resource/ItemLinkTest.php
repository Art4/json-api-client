<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\ItemLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ItemLinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();

		// Mock parent
		$this->parent = $this->getMockBuilder('Art4\JsonApiClient\AccessInterface')
			->getMock();
	}

	/**
	 * @test parsing of all properties
	 */
	public function testParsingPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->custom = 'http://example.org/custom';
		$object->related = new \stdClass();

		$link = new ItemLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ItemLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'custom', 'related'));

		$this->assertTrue($link->has('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->has('custom'));
		$this->assertSame($link->get('custom'), 'http://example.org/custom');
		$this->assertTrue($link->has('related'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('related'));

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'custom' => $link->get('custom'),
			'related' => $link->get('related'),
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

		$link = new ItemLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'ItemLink has to be an object, "' . gettype($input) . '" given.'
		);

		$link->parse($input);
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
		$object->input = $input;

		$link = new ItemLink($this->manager, $this->parent);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
		);

		$link->parse($object);
	}

	/**
	 * @test
	 */
	public function testGetOnANonExistingKeyThrowsException()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';

		$link = new ItemLink($this->manager, $this->parent);
		$link->parse($object);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
