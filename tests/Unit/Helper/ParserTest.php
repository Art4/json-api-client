<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Tests\Unit\Helper;

use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\Parser;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\Document;

class ParserTest extends TestCase
{
    /**
     * @test parseResponseBody() with valid JSON API returns Document Object
     */
    public function testParseResponseBodyWithValidJsonapiReturnsDocument()
    {
        $jsonapi = '{"meta":{}}';

        $this->assertInstanceOf(Document::class, Parser::parseResponseString($jsonapi));
    }

    /**
     * @test parseResponseBody throw Exception if input is invalid jsonapi
     */
    public function testParseResponseBodyWithInvalidJsonapiThrowsException()
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Document has to be an object, "array" given.'
        );

        $output = Parser::parseResponseString($invalid_jsonapi);
    }

    /**
     * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
     */
    public function testParseResponseBodyWithInvalidJsonThrowsException()
    {
        $invalid_json = 'invalid_json_string';

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'Unable to parse JSON data: JSON_ERROR_SYNTAX - Syntax error, malformed JSON'
        );

        $output = Parser::parseResponseString($invalid_json);
    }

    /**
     * @test isValidResponseBody() with valid JSON API returns true
     */
    public function testIsValidResponseBodyWithValidJsonapi()
    {
        $jsonapi = '{"meta":{}}';

        $this->assertTrue(Parser::isValidResponseString($jsonapi));
    }

    /**
     * @test isValidResponseBody() with invalid jsonapi
     */
    public function testIsValidResponseBodyWithInvalidJsonapi()
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->assertFalse(Parser::isValidResponseString($invalid_jsonapi));
    }

    /**
     * @test isValidResponseBody() with invalid json
     */
    public function testIsValidResponseBodyWithInvalidJson()
    {
        $invalid_json = 'invalid_json_string';

        $this->assertFalse(Parser::isValidResponseString($invalid_json));
    }

    /**
     * @test parseRequestBody() with valid JSON API returns Document Object
     */
    public function testParseRequestBodyWithValidJsonapiReturnsDocument()
    {
        $jsonapi = '{"meta":{}}';

        $this->assertInstanceOf(Document::class, Parser::parseRequestString($jsonapi));
    }

    /**
     * @test parseRequestBody() throw Exception if input is invalid jsonapi
     */
    public function testParseRequestBodyWithInvalidJsonapiThrowsException()
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Document has to be an object, "array" given.'
        );

        $output = Parser::parseRequestString($invalid_jsonapi);
    }

    /**
     * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
     */
    public function testParseRequestBodyWithInvalidJsonThrowsException()
    {
        $invalid_json = 'invalid_json_string';

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'Unable to parse JSON data: JSON_ERROR_SYNTAX - Syntax error, malformed JSON'
        );

        $output = Parser::parseRequestString($invalid_json);
    }

    /**
     * @test isValidRequestBody() with valid JSON API returns true
     */
    public function testIsValidRequestBodyWithValidJsonapi()
    {
        $jsonapi = '{"meta":{}}';

        $this->assertTrue(Parser::isValidRequestString($jsonapi));
    }

    /**
     * @test isValidRequestBody() with invalid jsonapi
     */
    public function testIsValidRequestBodyWithInvalidJsonapi()
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->assertFalse(Parser::isValidRequestString($invalid_jsonapi));
    }

    /**
     * @test isValidRequestBody() with invalid json
     */
    public function testIsValidRequestBodyWithInvalidJson()
    {
        $invalid_json = 'invalid_json_string';

        $this->assertFalse(Parser::isValidRequestString($invalid_json));
    }
}
