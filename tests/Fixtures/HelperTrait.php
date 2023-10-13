<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Factory;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Tests\Fixtures\Factory as FixtureFactory;

/**
 * Helper Trait
 */
trait HelperTrait
{
    protected Manager $manager;

    protected Factory $factory;

    protected Accessable $parent;

    /**
     * Json Values Provider
     *
     * @see http://json.org/
     */
    public static function jsonValuesProvider(): array
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
    public static function jsonValuesProviderWithoutObject(): array
    {
        $data = static::jsonValuesProvider();

        unset($data[0]);

        return array_values($data);
    }

    /**
     * Json Values Provider but without the array
     *
     * @see http://json.org/
     */
    public static function jsonValuesProviderWithoutArray(): array
    {
        $data = static::jsonValuesProvider();

        unset($data[1]);

        return array_values($data);
    }

    /**
     * Json Values Provider but without the string
     *
     * @see http://json.org/
     */
    public static function jsonValuesProviderWithoutString(): array
    {
        $data = static::jsonValuesProvider();

        unset($data[2]);

        return array_values($data);
    }

    /**
     * Json Values Provider but without the object and string
     *
     * @see http://json.org/
     */
    public static function jsonValuesProviderWithoutObjectAndString(): array
    {
        $data = static::jsonValuesProvider();

        unset($data[0]);
        unset($data[2]);

        return array_values($data);
    }

    /**
     * Json Values as string Provider
     *
     * @see http://json.org/
     */
    public static function jsonValuesAsStringProvider(): array
    {
        return [
            ['{}'],
            ['[]'],
            ['""'],
            ['456'],
            ['159.654'],
            ['-15E-3'],
            ['true'],
            ['false'],
            ['null'],
        ];
    }

    /**
     * Json Values as string Provider but without the object
     *
     * @see http://json.org/
     */
    public static function jsonValuesAsStringProviderWithoutObject(): array
    {
        $data = static::jsonValuesAsStringProvider();

        unset($data[0]);

        return array_values($data);
    }

    /**
     * Builds a Manager Mock
     */
    public function buildManagerMock()
    {
        // Mock factory
        $factory = new FixtureFactory();
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
    public function setUpManagerMock(): void
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
