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

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Collection Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorCollection implements ErrorCollectionInterface
{
    use AccessTrait;

    /**
     * @var DataContainerInterface
     */
    protected $container;

    /**
     * @var FactoryManagerInterface
     */
    protected $manager;

    /**
     * Sets the manager and parent
     *
     * @param FactoryManagerInterface $manager The manager
     * @param AccessInterface         $parent  The parent
     */
    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
    {
        $this->manager = $manager;

        $this->container = new DataContainer();
    }

    /**
     * Parses the data for this element
     *
     * @param mixed $object The data
     * @param mixed $errors
     *
     * @throws ValidationException
     *
     * @return self
     */
    public function parse($errors)
    {
        if (! is_array($errors)) {
            throw new ValidationException('Errors for a collection has to be in an array, "' . gettype($errors) . '" given.');
        }

        if (count($errors) === 0) {
            throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
        }

        foreach ($errors as $err) {
            $error = $this->manager->getFactory()->make(
                'Error',
                [$this->manager, $this]
            );
            $error->parse($err);

            $this->container->set('', $error);
        }

        return $this;
    }

    /**
     * Get a value by the key of this document
     *
     * @param string $key The key of the value
     *
     * @return mixed The value
     */
    public function get($key)
    {
        try {
            return $this->container->get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in this collection.');
        }
    }
}
