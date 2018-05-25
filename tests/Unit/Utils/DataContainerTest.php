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

namespace Art4\JsonApiClient\Utils\Tests;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\Utils\DataContainer;

class DataContainerTest extends TestCase
{
    /**
     * @test
     */
    public function testSetAsArray()
    {
        $data = new DataContainer;

        $data->set('', 'element0');
        $data->set('', 'element1');

        $this->assertSame([0, 1], $data->getKeys());
    }

    /**
     * @test
     */
    public function testHasWithoutDataReturnsFalse()
    {
        $data = new DataContainer;

        $this->assertFalse($data->has('key'));
    }

    /**
     * @test
     */
    public function testHasWithObjectKeyReturnsFalse()
    {
        $data = new DataContainer;

        $this->assertFalse($data->has(new \stdClass));
    }

    /**
     * @test
     */
    public function testHasWithArrayKeyReturnsFalse()
    {
        $data = new DataContainer;

        $this->assertFalse($data->has([]));
    }

    /**
     * @test
     */
    public function testHasWithUnknownDataReturnsFalse()
    {
        $data = new DataContainer;
        $data->set('foo', ['bar' => 'baz']);

        $this->assertTrue($data->has('foo'));
        $this->assertFalse($data->has('foo.bar'));
    }

    /**
     * @test
     */
    public function testHasWithUnknownDataInsideAccessableReturnsFalse()
    {
        $fuz = new DataContainer;
        $fuz->set('foo', ['bar' => 'baz']);

        $data = new DataContainer;
        $data->set('fuz', $fuz);

        $this->assertTrue($data->has('fuz.foo'));
        $this->assertFalse($data->has('fuz.foo.bar'));
    }

    /**
     * @test
     */
    public function testGetWithUnknownDataThrowsException()
    {
        $data = new DataContainer;
        $data->set('foo', ['bar' => 'baz']);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            'Could not get the value for the key "foo.bar"'
        );

        $data->get('foo.bar');
    }

    /**
     * @test
     */
    public function testGetWithUnknownDataInsideAccessableThrowsException()
    {
        $fuz = new DataContainer;
        $fuz->set('foo', ['bar' => 'baz']);

        $data = new DataContainer;
        $data->set('fuz', $fuz);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            'Could not get the value for the key "fuz.foo.bar"'
        );

        $data->get('fuz.foo.bar');
    }

    /**
     * @test
     */
    public function testAsArray()
    {
        $data = new DataContainer;
        $data->set('foo', 'bar');

        $this->assertSame(
            ['foo' => 'bar'],
            $data->asArray()
        );
    }
}
