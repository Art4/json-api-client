<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\AccessKey;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\ErrorCollection;
use PHPUnit\Framework\TestCase;

class ErrorCollectionTest extends TestCase
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
     * @test create
     */
    public function testCreate(): void
    {
        $errors = [
            new \stdClass(),
            new \stdClass(),
        ];

        $collection = new ErrorCollection($errors, $this->manager, $this->parent);

        $this->assertInstanceOf(ErrorCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), [0, 1]);

        $this->assertFalse($collection->has('string'));

        $this->assertTrue($collection->has(AccessKey::create(0)));

        $this->assertTrue($collection->has(0));
        $error = $collection->get(0);

        $this->assertInstanceOf(Accessable::class, $error);

        $this->assertTrue($collection->has(1));
        $error = $collection->get(1);

        $this->assertInstanceOf(Accessable::class, $error);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * @param mixed $input
     */
    public function testCreateWithoutArrayThrowsException($input): void
    {
        // Input must be an array with at least one object
        if (gettype($input) === 'array') {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage(
                'Errors array cannot be empty and MUST have at least one object'
            );
        } else {
            $this->expectException(ValidationException::class);
            $this->expectExceptionMessage(
                'Errors for a collection has to be in an array, "' . gettype($input) . '" given.'
            );
        }

        $collection = new ErrorCollection($input, $this->manager, $this->parent);
    }

    /**
     * @test get('resources') on an empty collection throws an exception
     */
    public function testGetErrorWithEmptyCollectionThrowsException(): void
    {
        $errors = [
            new \stdClass(),
        ];

        $collection = new ErrorCollection($errors, $this->manager, $this->parent);

        $this->assertInstanceOf(ErrorCollection::class, $collection);

        $this->assertFalse($collection->has(1));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"1" doesn\'t exist in this collection.'
        );

        $collection->get(1);
    }
}
