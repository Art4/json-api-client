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

namespace Art4\JsonApiClient\Tests\Fixtures;

class TestCase extends \PHPUnit\Framework\TestCase
{
	/**
	 * Returns a test double for the specified class.
	 *
	 * Shim for PHPUnit 4
	 *
	 * @param string $originalClassName
	 * @return PHPUnit_Framework_MockObject_MockObject
	 * @throws Exception
	 */
	protected function createMock($originalClassName)
	{
		if (is_callable('parent::createMock'))
		{
			return parent::createMock($originalClassName);
		}

		return $this->getMockBuilder($originalClassName)
			->disableOriginalConstructor()
			->disableOriginalClone()
			->disableArgumentCloning()
			->getMock();
	}

	/**
	 * Returns a mock object for the specified class.
	 *
	 * Shim for PHPUnit 6
	 *
	 * @param string     $originalClassName       Name of the class to mock.
	 * @param array|null $methods                 When provided, only methods whose names are in the array
	 *                                            are replaced with a configurable test double. The behavior
	 *                                            of the other methods is not changed.
	 *                                            Providing null means that no methods will be replaced.
	 * @param array      $arguments               Parameters to pass to the original class' constructor.
	 * @param string     $mockClassName           Class name for the generated test double class.
	 * @param bool       $callOriginalConstructor Can be used to disable the call to the original class' constructor.
	 * @param bool       $callOriginalClone       Can be used to disable the call to the original class' clone constructor.
	 * @param bool       $callAutoload            Can be used to disable __autoload() during the generation of the test double class.
	 * @param bool       $cloneArguments
	 * @param bool       $callOriginalMethods
	 * @param object     $proxyTarget
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject
	 *
	 * @throws PHPUnit_Framework_Exception
	 *
	 * @since  Method available since Release 3.0.0
	 */
	public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false, $callOriginalMethods = false, $proxyTarget = null)
	{
		if (is_callable('parent::getMock'))
		{
			return parent::getMock(
				$originalClassName,
				$methods,
				$arguments,
				$mockClassName,
				$callOriginalConstructor,
				$callOriginalClone,
				$callAutoload,
				$cloneArguments,
				$callOriginalMethods,
				$proxyTarget
			);
		}

		return $this->getMockBuilder($originalClassName)
			->getMock();
	}

	/**
	 * Shim for PHPUnit 6
	 *
	 * @param mixed  $exceptionName
	 * @param string $exceptionMessage
	 * @param int    $exceptionCode
	 */
	public function setExpectedException($exceptionName, $exceptionMessage = '', $exceptionCode = null)
	{
		if (is_callable('parent::setExpectedException'))
		{
			return parent::setExpectedException($exceptionName, $exceptionMessage, $exceptionCode);
		}

		$this->expectException($exceptionName);
		$this->expectExceptionMessage($exceptionMessage);

		if ( $exceptionCode !== null )
		{
			$this->expectExceptionCode($exceptionCode);
		}
	}
}
