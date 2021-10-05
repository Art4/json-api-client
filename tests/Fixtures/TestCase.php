<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2021  Artur Weigandt  https://wlabs.de/kontakt

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
     * Shim for PHPUnit 6
     *
     * @param mixed  $exceptionName
     * @param string $exceptionMessage
     * @param int    $exceptionCode
     */
    public function setExpectedException($exceptionName, $exceptionMessage = '', $exceptionCode = null)
    {
        if (is_callable('parent::setExpectedException')) {
            return parent::setExpectedException($exceptionName, $exceptionMessage, $exceptionCode);
        }

        $this->expectException($exceptionName);
        $this->expectExceptionMessage($exceptionMessage);

        if ($exceptionCode !== null) {
            $this->expectExceptionCode($exceptionCode);
        }
    }
}
