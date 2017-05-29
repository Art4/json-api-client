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

namespace Art4\JsonApiClient\Tests\Integration;

use Art4\JsonApiClient\Utils\Helper;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorParsingTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
	use HelperTrait;

	/**
	 * @test
	 */
	public function testParseErrors()
	{
		$string = $this->getJsonString('09_errors.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertTrue($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertFalse($document->has('data'));

		$errors = $document->get('errors');
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollectionInterface', $errors);
		$this->assertCount(3, $errors->getKeys());

		$this->assertTrue($errors->has('0'));
		$error0 = $errors->get('0');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error0);
		$this->assertCount(5, $error0->getKeys());
		$this->assertTrue($error0->has('code'));
		$this->assertSame('123', $error0->get('code'));
		$this->assertTrue($error0->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error0->get('source'));
		$this->assertTrue($error0->has('source.pointer'));
		$this->assertSame('/data/attributes/first-name', $error0->get('source.pointer'));
		$this->assertTrue($error0->has('title'));
		$this->assertSame('Value is too short', $error0->get('title'));
		$this->assertTrue($error0->has('detail'));
		$this->assertSame('First name must contain at least three characters.', $error0->get('detail'));
		$this->assertTrue($error0->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $error0->get('meta'));
		$this->assertSame('bar', $error0->get('meta.foo'));

		$this->assertTrue($errors->has('1'));
		$error1 = $errors->get('1');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error1);
		$this->assertCount(4, $error1->getKeys());
		$this->assertTrue($error1->has('code'));
		$this->assertSame('225', $error1->get('code'));
		$this->assertTrue($error1->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error1->get('source'));
		$this->assertTrue($error1->has('source.pointer'));
		$this->assertSame('/data/attributes/password', $error1->get('source.pointer'));
		$this->assertTrue($error1->has('title'));
		$this->assertSame('Passwords must contain a letter, number, and punctuation character.', $error1->get('title'));
		$this->assertTrue($error1->has('detail'));
		$this->assertSame('The password provided is missing a punctuation character.', $error1->get('detail'));

		$this->assertTrue($errors->has('2'));
		$error2 = $errors->get('2');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error2);
		$this->assertCount(3, $error2->getKeys());
		$this->assertTrue($error2->has('code'));
		$this->assertSame('226', $error2->get('code'));
		$this->assertTrue($error2->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error2->get('source'));
		$this->assertTrue($error2->has('source.pointer'));
		$this->assertSame('/data/attributes/password', $error2->get('source.pointer'));
		$this->assertTrue($error2->has('title'));
		$this->assertSame('Password and password confirmation do not match.', $error2->get('title'));
		$this->assertFalse($error2->has('detail'));

		$this->assertFalse($errors->has('3'));

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}
	/**
	 * @test
	 */
	public function testParseErrorWithLinks()
	{
		$string = $this->getJsonString('10_error_with_links.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertTrue($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertFalse($document->has('data'));

		$errors = $document->get('errors');
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollectionInterface', $errors);
		$this->assertCount(2, $errors->getKeys());

		$this->assertTrue($errors->has('0'));
		$error0 = $errors->get('0');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error0);
		$this->assertCount(4, $error0->getKeys());
		$this->assertTrue($error0->has('code'));
		$this->assertSame('123', $error0->get('code'));
		$this->assertTrue($error0->has('source'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSourceInterface', $error0->get('source'));
		$this->assertTrue($error0->has('source.pointer'));
		$this->assertSame('/data/attributes/first-name', $error0->get('source.pointer'));
		$this->assertTrue($error0->has('title'));
		$this->assertSame('Value is too short', $error0->get('title'));
		$this->assertTrue($error0->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLinkInterface', $error0->get('links'));
		$this->assertTrue($error0->has('links.about'));
		$this->assertSame('http://example.org/errors/123', $error0->get('links.about'));

		$this->assertTrue($errors->has('1'));
		$error1 = $errors->get('1');

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error1);
		$this->assertCount(2, $error1->getKeys());
		$this->assertTrue($error1->has('code'));
		$this->assertSame('124', $error1->get('code'));
		$this->assertTrue($error1->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLinkInterface', $error1->get('links'));
		$this->assertTrue($error1->has('links.about'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $error1->get('links.about'));
		$this->assertTrue($error1->has('links.about.href'));
		$this->assertSame('http://example.org/errors/124', $error1->get('links.about.href'));
		$this->assertTrue($error1->has('links.meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $error1->get('links.meta'));
		$this->assertTrue($error1->has('links.meta.href'));
		$this->assertSame('http://example.org/meta', $error1->get('links.meta.href'));

		$this->assertFalse($errors->has('2'));

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}
}
