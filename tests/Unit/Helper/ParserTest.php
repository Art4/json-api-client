<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Helper;

use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Helper\Parser;
use Art4\JsonApiClient\V1\Document;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @test parseResponseBody() with valid JSON API returns Document Object
     */
    public function testParseResponseBodyWithValidJsonapiReturnsDocument(): void
    {
        $jsonapi = '{"meta":{}}';

        $this->assertInstanceOf(Document::class, Parser::parseResponseString($jsonapi));
    }

    /**
     * @test parseResponseBody throw Exception if input is invalid jsonapi
     */
    public function testParseResponseBodyWithInvalidJsonapiThrowsException(): void
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'JSON must contain an object (e.g. `{}`).'
        );

        $output = Parser::parseResponseString($invalid_jsonapi);
    }

    /**
     * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
     */
    public function testParseResponseBodyWithInvalidJsonThrowsException(): void
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
    public function testIsValidResponseBodyWithValidJsonapi(): void
    {
        $jsonapi = '{"meta":{}}';

        $this->assertTrue(Parser::isValidResponseString($jsonapi));
    }

    /**
     * @test isValidResponseBody() with invalid jsonapi
     */
    public function testIsValidResponseBodyWithInvalidJsonapi(): void
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->assertFalse(Parser::isValidResponseString($invalid_jsonapi));
    }

    /**
     * @test isValidResponseBody() with invalid json
     */
    public function testIsValidResponseBodyWithInvalidJson(): void
    {
        $invalid_json = 'invalid_json_string';

        $this->assertFalse(Parser::isValidResponseString($invalid_json));
    }

    /**
     * @test parseRequestBody() with valid JSON API returns Document Object
     */
    public function testParseRequestBodyWithValidJsonapiReturnsDocument(): void
    {
        $jsonapi = '{"meta":{}}';

        $this->assertInstanceOf(Document::class, Parser::parseRequestString($jsonapi));
    }

    /**
     * @test parseRequestBody() throw Exception if input is invalid jsonapi
     */
    public function testParseRequestBodyWithInvalidJsonapiThrowsException(): void
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'JSON must contain an object (e.g. `{}`).'
        );

        $output = Parser::parseRequestString($invalid_jsonapi);
    }

    /**
     * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
     */
    public function testParseRequestBodyWithInvalidJsonThrowsException(): void
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
    public function testIsValidRequestBodyWithValidJsonapi(): void
    {
        $jsonapi = '{"meta":{}}';

        $this->assertTrue(Parser::isValidRequestString($jsonapi));
    }

    /**
     * @test isValidRequestBody() with invalid jsonapi
     */
    public function testIsValidRequestBodyWithInvalidJsonapi(): void
    {
        $invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

        $this->assertFalse(Parser::isValidRequestString($invalid_jsonapi));
    }

    /**
     * @test isValidRequestBody() with invalid json
     */
    public function testIsValidRequestBodyWithInvalidJson(): void
    {
        $invalid_json = 'invalid_json_string';

        $this->assertFalse(Parser::isValidRequestString($invalid_json));
    }
}
