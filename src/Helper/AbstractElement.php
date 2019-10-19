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

namespace Art4\JsonApiClient\Helper;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;

/**
 * AbstractElement
 *
 * @internal
 */
abstract class AbstractElement implements Accessable, Element
{
    use AccessableTrait;

    /**
     * @var \Art4\JsonApiClient\Manager
     */
    private $manager;

    /**
     * @var \Art4\JsonApiClient\Accessable
     */
    private $parent;

    /**
     * Sets the manager and parent
     *
     * @param mixed                          $data    The data for this Element
     * @param \Art4\JsonApiClient\Manager    $manager The manager
     * @param \Art4\JsonApiClient\Accessable $parent  The parent
     */
    public function __construct($data, Manager $manager, Accessable $parent)
    {
        $this->manager = $manager;
        $this->parent = $parent;

        $this->parse($data);
    }

    /**
     * Returns the Manager
     *
     * @return \Art4\JsonApiClient\Manager
     */
    protected function getManager()
    {
        return $this->manager;
    }

    /**
     * Get the parent
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * Create an element
     *
     * @param mixed $name
     * @param mixed $data
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    protected function create($name, $data)
    {
        return $this->getManager()->getFactory()->make(
            $name,
            [$data, $this->getManager(), $this]
        );
    }

    /**
     * Parse the data
     *
     * @param mixed $data
     */
    abstract protected function parse($data);
}
