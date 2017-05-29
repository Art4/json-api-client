<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Tests\Unit;

use Art4\JsonApiClient\ResourceNull;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ResourceNullTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
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
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithDataProvider($input)
	{
		$resource = new ResourceNull($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$resource->parse($input);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceNull', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $resource);

		$this->assertFalse($resource->has('something'));
		$this->assertSame($resource->getKeys(), array());

		$this->assertSame($resource->asArray(), null);

		// Test full array
		$this->assertSame($resource->asArray(true), null);
	}

	/**
	 * @test get throws Exception
	 */
	public function testGetThrowsException()
	{
		$resource = new ResourceNull($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$resource->parse(null);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'A ResourceNull has no values.'
		);

		$resource->get('something');
	}
}
