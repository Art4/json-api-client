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
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\ErrorCollection;

class ErrorCollectionTest extends TestCase
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
     * @test create
     */
    public function testCreate()
    {
        $errors = [
            new \stdClass(),
            new \stdClass(),
        ];

        $collection = new ErrorCollection($errors, $this->manager, $this->parent);

        $this->assertInstanceOf(ErrorCollection::class, $collection);
        $this->assertInstanceOf(Accessable::class, $collection);

        $this->assertSame($collection->getKeys(), [0, 1]);

        $this->assertFalse($collection->has(new \stdClass));
        $this->assertFalse($collection->has([]));
        $this->assertFalse($collection->has('string'));

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
    public function testCreateWithoutArrayThrowsException($input)
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
    public function testGetErrorWithEmptyCollectionThrowsException()
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
