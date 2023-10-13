<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\FactoryException;
use Art4\JsonApiClient\Factory as FactoryInterface;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\V1\Factory;
use Art4\JsonApiClient\V1\ResourceNull;
use PHPUnit\Framework\TestCase;
use stdClass;

class FactoryTest extends TestCase
{
    /**
     * @test
     */
    public function testInjectACustomClass()
    {
        $factory = new Factory([
            'Default' => ResourceNull::class,
        ]);

        $resource = $factory->make('Default', [
            null,
            $this->createMock(Manager::class),
            $this->createMock(Accessable::class),
        ]);

        $this->assertInstanceOf(FactoryInterface::class, $factory);
        $this->assertInstanceOf(ResourceNull::class, $resource);
    }

    /**
     * @test parse throw Exception if input is invalid jsonapi
     */
    public function testMakeAnUndefindedClassThrowsException()
    {
        $factory = new Factory();

        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage(
            '"NotExistent" is not a registered class'
        );

        $class = $factory->make('NotExistent');
    }

    public function testMakeWithClassNotImplementingAccessableThrowsException()
    {
        $factory = new Factory([
            'Default' => stdClass::class,
        ]);

        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage(
            'stdClass must be instance of `Art4\JsonApiClient\Accessable`'
        );

        $class = $factory->make('Default');
    }
}
