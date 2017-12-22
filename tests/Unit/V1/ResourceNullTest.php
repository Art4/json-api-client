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
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\V1Factory;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\ResourceNull;

class ResourceNullTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * @param mixed $input
     */
    public function testCreateWithDataProvider($input)
    {
        $resource = new ResourceNull(
            $input,
            $this->manager,
            $this->createMock(Accessable::class)
        );

        $this->assertInstanceOf(Accessable::class, $resource);

        $this->assertFalse($resource->has('something'));
        $this->assertSame([], $resource->getKeys());
    }

    /**
     * @test get throws Exception
     */
    public function testGetThrowsException()
    {
        $resource = new ResourceNull(
            null,
            $this->manager,
            $this->createMock(Accessable::class)
        );

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('A ResourceNull has no values.');

        $resource->get('something');
    }
}
