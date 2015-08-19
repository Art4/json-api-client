<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Null;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class NullTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithDataProvider($input)
	{
		$resource = new Null($input);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Null', $resource);
	}
}
