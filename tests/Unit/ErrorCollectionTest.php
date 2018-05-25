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

namespace Art4\JsonApiClient\Tests\Unit;

use Art4\JsonApiClient\ErrorCollection;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorCollectionTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->manager = $this->buildManagerMock();
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

        $collection = new ErrorCollection($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $collection->parse($errors);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorCollection', $collection);
        $this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);

        $this->assertTrue(count($collection->asArray()) === 2);
        $this->assertSame($collection->getKeys(), [0, 1]);

        $this->assertFalse($collection->has(new \stdClass));
        $this->assertFalse($collection->has([]));
        $this->assertFalse($collection->has('string'));

        $this->assertTrue($collection->has(0));
        $error = $collection->get(0);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error);

        $this->assertTrue($collection->has(1));
        $error = $collection->get(1);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorInterface', $error);

        $this->assertSame([
            $collection->get(0),
            $collection->get(1),
        ], $collection->asArray());
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
            $this->setExpectedException(
                'Art4\JsonApiClient\Exception\ValidationException',
                'Errors array cannot be empty and MUST have at least one object'
            );
        } else {
            $this->setExpectedException(
                'Art4\JsonApiClient\Exception\ValidationException',
                'Errors for a collection has to be in an array, "' . gettype($input) . '" given.'
            );
        }

        $collection = new ErrorCollection($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $collection->parse($input);
    }

    /**
     * @test get('resources') on an empty collection throws an exception
     */
    public function testGetErrorWithEmptyCollectionThrowsException()
    {
        $errors = [
            new \stdClass(),
        ];

        $collection = new ErrorCollection($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $collection->parse($errors);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorCollection', $collection);

        $this->assertFalse($collection->has(1));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"1" doesn\'t exist in this collection.'
        );

        $collection->get(1);
    }
}
