<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

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

use Art4\JsonApiClient\Utils\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testCreateReturnsSelf()
	{
		$manager = new Manager;

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\ManagerInterface', $manager);
		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryManagerInterface', $manager);
	}

	/**
	 * @test
	 */
	public function testCreateWithConstructorReturnsSelf()
	{
		$factory = $this->getMockBuilder('Art4\JsonApiClient\Utils\FactoryInterface')
			->getMock();

		$manager = new Manager($factory);

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\ManagerInterface', $manager);
		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryManagerInterface', $manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $manager->getFactory());
	}

	/**
	 * @test
	 */
	public function testSetFactoryReturnsSelf()
	{
		$factory = $this->getMockBuilder('Art4\JsonApiClient\Utils\FactoryInterface')
			->getMock();

		$manager = new Manager;

		$this->assertSame($manager, $manager->setFactory($factory));
	}

	/**
	 * @test
	 */
	public function testGetFactoryReturnsFactoryInterface()
	{
		$factory = $this->getMockBuilder('Art4\JsonApiClient\Utils\FactoryInterface')
			->getMock();

		$manager = (new Manager)->setFactory($factory);

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $manager->getFactory());
	}

	/**
	 * @test
	 */
	public function testGetFactoryWitoutSetReturnsFactoryInterface()
	{
		$manager = new Manager;

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $manager->getFactory());
	}

	/**
	 * @test
	 */
	public function testParseReturnsDocument()
	{
		$manager = new Manager;

		$jsonapi_string = '{"meta":{}}';

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $manager->parse($jsonapi_string));
	}
}
