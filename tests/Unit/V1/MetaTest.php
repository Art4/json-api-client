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
use Art4\JsonApiClient\V1\Meta;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
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

        $meta = new Meta(
            $object,
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(Accessable::class, $meta);
        $this->assertTrue($meta->has('object'));
        $this->assertIsObject($meta->get('object'));
        $this->assertTrue($meta->has('array'));
        $this->assertIsArray($meta->get('array'));
        $this->assertTrue($meta->has('string'));
        $this->assertIsString($meta->get('string'));
        $this->assertTrue($meta->has('number_int'));
        $this->assertIsInt($meta->get('number_int'));
        $this->assertTrue($meta->has('number_float'));
        $this->assertIsFloat($meta->get('number_float'));
        $this->assertTrue($meta->has('true'));
        $this->assertTrue($meta->get('true'));
        $this->assertTrue($meta->has('false'));
        $this->assertFalse($meta->get('false'));
        $this->assertTrue($meta->has('null'));
        $this->assertNull($meta->get('null'));

        $this->assertSame(
            ['object', 'array', 'string', 'number_int', 'number_float', 'true', 'false', 'null'],
            $meta->getKeys()
        );
    }

    /**
     * The value of each meta member MUST be an object (a "meta object").
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithoutObjectThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Meta has to be an object, "' . gettype($input) . '" given.');

        $meta = new Meta($input, $this->manager, $this->parent);
    }

    /**
     * @test get() with not existing key throws an exception
     */
    public function testGetWithNotExistingKeyThrowsException(): void
    {
        $object = new \stdClass();

        $meta = new Meta($object, $this->manager, $this->parent);

        $this->assertFalse($meta->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('"something" doesn\'t exist in this object.');

        $meta->get('something');
    }
}
