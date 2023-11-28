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
use Art4\JsonApiClient\V1\Attributes;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AttributesTest extends TestCase
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
     * @test create with object
     */
    public function testCreateWithObject(): void
    {
        $object = new \stdClass();
        $object->object = new \stdClass();
        $object->array = [];
        $object->string = 'string';
        $object->number_int = 654;
        $object->number_float = 654.321;
        $object->true = true;
        $object->false = false;
        $object->null = null;

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(Accessable::class, $attributes);
        $this->assertTrue($attributes->has('object'));
        $this->assertIsObject($attributes->get('object'));
        $this->assertTrue($attributes->has('array'));
        $this->assertIsArray($attributes->get('array'));
        $this->assertTrue($attributes->has('string'));
        $this->assertIsString($attributes->get('string'));
        $this->assertTrue($attributes->has('number_int'));
        $this->assertIsInt($attributes->get('number_int'));
        $this->assertTrue($attributes->has('number_float'));
        $this->assertIsFloat($attributes->get('number_float'));
        $this->assertTrue($attributes->has('true'));
        $this->assertTrue($attributes->get('true'));
        $this->assertTrue($attributes->has('false'));
        $this->assertFalse($attributes->get('false'));
        $this->assertTrue($attributes->has('null'));
        $this->assertNull($attributes->get('null'));
        $this->assertSame([
            'object',
            'array',
            'string',
            'number_int',
            'number_float',
            'true',
            'false',
            'null',
        ], $attributes->getKeys());
    }

    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithDataProvider(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Attributes has to be an object, "' . gettype($input) . '" given.'
        );

        $attributes = new Attributes(
            $input,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithTypePropertyThrowsException(): void
    {
        $object = new \stdClass();
        $object->type = 'posts';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithIdPropertyThrowsException(): void
    {
        $object = new \stdClass();
        $object->id = '5';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithRelationshipsPropertyThrowsException(): void
    {
        $object = new \stdClass();
        $object->relationships = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithLinksPropertyThrowsException(): void
    {
        $object = new \stdClass();
        $object->links = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: ' .
            '`type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->pages = '1126';

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );

        $this->assertFalse($attributes->has('foobar'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"foobar" doesn\'t exist in this object.'
        );

        $attributes->get('foobar');
    }
}
