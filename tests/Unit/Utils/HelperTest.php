<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Utils\Tests;

use Art4\JsonApiClient\Utils\Helper;

class HelperTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
	/**
	 * @test parse() with valid JSON API returns Document Object
	 */
	public function testParseWithValidJsonapiReturnsDocument()
	{
		$jsonapi = '{"meta":{}}';

		$this->assertInstanceOf('Art4\JsonApiClient\Document', Helper::parse($jsonapi));
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * @test parse throw Exception if input is invalid jsonapi
	 */
	public function testParseWithInvalidJsonapiThrowsException()
	{
		$invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

		$output = Helper::parse($invalid_jsonapi);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
	 */
	public function testParseWithInvalidJsonThrowsException()
	{
		$invalid_json = 'invalid_json_string';

		$output = Helper::parse($invalid_json);
	}

	/**
	 * @test isValid() with valid JSON API returns true
	 */
	public function testIsValidWithValidJsonapi()
	{
		$jsonapi = '{"meta":{}}';

		$this->assertTrue(Helper::isValid($jsonapi));
	}

	/**
	 * @test isValid() with invalid jsonapi
	 */
	public function testIsValidWithInvalidJsonapi()
	{
		$invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

		$this->assertFalse(Helper::isValid($invalid_jsonapi));
	}

	/**
	 * @test isValid() with invalid json
	 */
	public function testIsValidWithInvalidJson()
	{
		$invalid_json = 'invalid_json_string';

		$this->assertFalse(Helper::isValid($invalid_json));
	}
}
