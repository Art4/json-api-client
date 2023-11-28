<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp(): void
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test create with object returns self
     */
    public function testCreateWithObjectReturnsSelf(): void
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

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this error object.'
        );

        $error->get('something');
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     */
    public function testCreateWithoutObjectThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Error has to be an object, "' . gettype($input) . '" given.'
        );

        $error = new Error($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     */
    public function testCreateIdWithoutStringThrowsException(mixed $input): void
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
     */
    public function testCreateStatusWithoutStringThrowsException(mixed $input): void
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
     */
    public function testCreateCodeWithoutStringThrowsException(mixed $input): void
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
     */
    public function testCreateTitleWithoutStringThrowsException(mixed $input): void
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
     */
    public function testCreateDetailWithoutStringThrowsException(mixed $input): void
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
