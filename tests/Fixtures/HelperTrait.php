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

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Tests\Fixtures\Factory as FixtureFactory;

/**
 * Helper Trait
 */
trait HelperTrait
{
    /**
     * Json Values Provider
     *
     * @see http://json.org/
     */
    public function jsonValuesProvider()
    {
        return [
            [new \stdClass()],
            [[]],
            ['string'],
            [456],
            [159.654],
            [-15E-3],
            [true],
            [false],
            [null],
        ];
    }

    /**
     * Json Values Provider but without the object
     *
     * @see http://json.org/
     */
    public function jsonValuesProviderWithoutObject()
    {
        return [
            [[]],
            ['string'],
            [456],
            [159.654],
            [-15E-3],
            [true],
            [false],
            [null],
        ];
    }

    /**
     * Json Values Provider but without the array
     *
     * @see http://json.org/
     */
    public function jsonValuesProviderWithoutArray()
    {
        return [
            [new \stdClass],
            ['string'],
            [456],
            [159.654],
            [-15E-3],
            [true],
            [false],
            [null],
        ];
    }

    /**
     * Json Values Provider but without the string
     *
     * @see http://json.org/
     */
    public function jsonValuesProviderWithoutString()
    {
        return [
            [new \stdClass()],
            [[]],
            [456],
            [159.654],
            [-15E-3],
            [true],
            [false],
            [null],
        ];
    }

    /**
     * Json Values Provider but without the object and string
     *
     * @see http://json.org/
     */
    public function jsonValuesProviderWithoutObjectAndString()
    {
        return [
            [[]],
            [456],
            [159.654],
            [-15E-3],
            [true],
            [false],
            [null],
        ];
    }

    /**
     * Builds a Manager Mock
     */
    public function buildManagerMock()
    {
        // Mock factory
        $factory = new FixtureFactory;
        $factory->testcase = $this;

        // Mock Manager
        $manager = $this->createMock('Art4\JsonApiClient\Utils\FactoryManagerInterface');

        $manager->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($factory));

        $manager->expects($this->any())
            ->method('getConfig')
            ->with('optional_item_id')
            ->willReturn(false);

        return $manager;
    }

    /**
     * Builds a Manager Mock and set it into the TestCase
     */
    public function setUpManagerMock()
    {
        // Mock factory
        $factory = new V1Factory($this);

        // Mock Manager
        $this->manager = $this->createMock(Manager::class);

        $this->manager->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($factory));

        $this->manager->expects($this->any())
            ->method('getParam')
            ->with('optional_item_id')
            ->willReturn(false);
    }

    /**
     * returns a json string from a file
     *
     * @param mixed $filename
     */
    protected function getJsonString($filename)
    {
        return file_get_contents(__DIR__ . '/../files/' . $filename);
    }
}
