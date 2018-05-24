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

namespace Art4\JsonApiClient\ForwardCompatibility\Tests;

use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\ForwardCompatibility\Manager;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

class ManagerTest extends TestCase
{
    /**
     * @test
     */
    public function testCreateWithConstructorReturnsSelf()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->assertInstanceOf('Art4\JsonApiClient\Manager', $manager);
        $this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryManagerInterface', $manager);

        $this->assertInstanceOf(Factory::class, $manager->getFactory());
    }

    /**
     * @test
     */
    public function testSetFactoryReturnsSelf()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            '"Art4\JsonApiClient\ForwardCompatibility\Manager::setFactory" is not implemented.'
        );

        $manager->setFactory($this->createMock('Art4\JsonApiClient\Utils\FactoryInterface'));
    }

    /**
     * @test
     */
    public function testParseReturnsDocument()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->expectException(\Art4\JsonApiClient\Exception\ValidationException::class);
        $this->expectExceptionMessage(
            '"Art4\JsonApiClient\ForwardCompatibility\Manager::parseString" is not implemented.'
        );

        $manager->parseString('{"meta":{}}');
    }

    /**
     * @test
     */
    public function testGetParamReturnsValue()
    {
        $manager = new Manager($this->createMock(Factory::class), ['optional_item_id' => false]);

        $this->assertSame(false, $manager->getParam('optional_item_id', 'default'));
    }

    /**
     * @test
     */
    public function testGetConfigReturnsValue()
    {
        $manager = new Manager($this->createMock(Factory::class), ['optional_item_id' => false]);

        $this->assertSame(false, $manager->getConfig('optional_item_id'));
    }

    /**
     * @test
     */
    public function testGetInvalidParamReturnsDefault()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->assertSame('default', $manager->getParam('invalid_key', 'default'));
    }

    /**
     * @test
     */
    public function testGetInvalidConfigReturnsNull()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->assertSame(null, $manager->getConfig('invalid_key'));
    }

    /**
     * @test
     */
    public function testSetConfigThrowsException()
    {
        $manager = new Manager($this->createMock(Factory::class), []);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            '"Art4\JsonApiClient\ForwardCompatibility\Manager::setConfig" is not implemented.'
        );

        $manager->setConfig('optional_item_id', true);
    }
}
