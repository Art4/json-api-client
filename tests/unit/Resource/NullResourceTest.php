<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\NullResource;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class NullTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithDataProvider($input)
	{
		$resource = new NullResource($input);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\NullResource', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $resource);

		$this->assertTrue($resource->isNull());
		$this->assertFalse($resource->isIdentifier());
		$this->assertFalse($resource->isItem());
		$this->assertFalse($resource->isCollection());

		$this->assertSame($resource->asArray(), null);
	}
}
