<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorLink;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class ErrorLinkTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test only 'about' property' can exist
	 *
	 * An error object MAY have the following members:
	 * - links: a links object containing the following members:
	 *   - about: a link that leads to further details about this particular occurrence of the problem.
	 */
	public function testOnlyAboutPropertyExists()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->href = 'http://example.org/href';
		$object->about = 'http://example.org/about';

		$link = new ErrorLink($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('about'));

		$this->assertFalse($link->has('href'));
		$this->assertFalse($link->has('meta'));
		$this->assertTrue($link->has('about'));
		$this->assertSame($link->get('about'), 'http://example.org/about');

		$this->assertSame($link->asArray(), array(
			'about' => $link->get('about'),
		));
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

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$link = new ErrorLink($object);
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

		$link = new ErrorLink($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
		$this->assertSame($link->getKeys(), array('about'));

		$this->assertTrue($link->has('about'));
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $link->get('about'));
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

		$link = new ErrorLink($input);
	}
}
