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

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\Error;

class ErrorTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test create with object returns self
     */
    public function testCreateWithObjectReturnsSelf()
    {
        $object = new \stdClass();
        $object->id = 'id';
        $object->links = new \stdClass();
        $object->links->about = 'http://example.org/about';
        $object->status = 'status';
        $object->code = 'code';
        $object->title = 'title';
        $object->detail = 'detail';
        $object->source = new \stdClass();
        $object->meta = new \stdClass();

        $error = new Error($object, $this->manager, $this->parent);

        $this->assertInstanceOf('Art4\JsonApiClient\V1\Error', $error);
        $this->assertInstanceOf(Accessable::class, $error);
        $this->assertSame($error->getKeys(), ['id', 'links', 'status', 'code', 'title', 'detail', 'source', 'meta']);

        $this->assertTrue($error->has('id'));
        $this->assertSame($error->get('id'), 'id');
        $this->assertTrue($error->has('links'));
        $this->assertInstanceOf(Accessable::class, $error->get('links'));
        $this->assertTrue($error->has('status'));
        $this->assertSame($error->get('status'), 'status');
        $this->assertTrue($error->has('code'));
        $this->assertSame($error->get('code'), 'code');
        $this->assertTrue($error->has('title'));
        $this->assertSame($error->get('title'), 'title');
        $this->assertTrue($error->has('detail'));
        $this->assertSame($error->get('detail'), 'detail');
        $this->assertTrue($error->has('source'));
        $this->assertInstanceOf(Accessable::class, $error->get('source'));
        $this->assertTrue($error->has('meta'));
        $this->assertInstanceOf(Accessable::class, $error->get('meta'));

        // test get() with not existing key throws an exception
        $this->assertFalse($error->has('something'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"something" doesn\'t exist in this error object.'
        );

        $error->get('something');
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Error has to be an object, "' . gettype($input) . '" given.'
        );

        $error = new Error($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * @param mixed $input
     */
    public function testCreateIdWithoutStringThrowsException($input)
    {
        $object = new \stdClass();
        $object->id = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "id" has to be a string, "' . gettype($input) . '" given.'
        );

        $error = new Error($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * @param mixed $input
     */
    public function testCreateStatusWithoutStringThrowsException($input)
    {
        $object = new \stdClass();
        $object->status = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "status" has to be a string, "' . gettype($input) . '" given.'
        );

        $error = new Error($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * @param mixed $input
     */
    public function testCreateCodeWithoutStringThrowsException($input)
    {
        $object = new \stdClass();
        $object->code = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "code" has to be a string, "' . gettype($input) . '" given.'
        );

        $error = new Error($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * @param mixed $input
     */
    public function testCreateTitleWithoutStringThrowsException($input)
    {
        $object = new \stdClass();
        $object->title = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "title" has to be a string, "' . gettype($input) . '" given.'
        );

        $error = new Error($object, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     *
     * @param mixed $input
     */
    public function testCreateDetailWithoutStringThrowsException($input)
    {
        $object = new \stdClass();
        $object->detail = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'property "detail" has to be a string, "' . gettype($input) . '" given.'
        );

        $error = new Error($object, $this->manager, $this->parent);
    }
}
