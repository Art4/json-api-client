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

namespace Art4\JsonApiClient\Tests\Fixtures;

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
		return array(
			array(new \stdClass()),
			array(array()),
			array('string'),
			array(456),
			array(159.654),
			array(-15E-3),
			array(true),
			array(false),
			array(null),
		);
	}

	/**
	 * Builds a Manager Mock
	 */
	public function buildManagerMock()
	{
		// Mock factory
		$factory = new Factory;
		$factory->testcase = $this;

		// Mock Manager
		$manager = $this->getMockBuilder('Art4\JsonApiClient\Utils\FactoryManagerInterface')
			->getMock();

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
	 * returns a json string from a file
	 */
	protected function getJsonString($filename)
	{
		return file_get_contents(__DIR__ . '/../files/' . $filename);
	}
}
