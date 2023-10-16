<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Exception\AccessException;

/**
 * Null Resource
 */
final class ResourceNull implements Accessable, Element
{
    /** @var mixed */
    private $data;
    private Manager $manager;
    private Accessable $parent;

    /**
     * Constructor
     *
     * @param mixed                          $data    The data for this Element
     * @param \Art4\JsonApiClient\Manager    $manager The manager
     * @param \Art4\JsonApiClient\Accessable $parent  The parent
     */
    public function __construct($data, Manager $manager, Accessable $parent)
    {
        $this->data = $data;
        $this->manager = $manager;
        $this->parent = $parent;
    }

    /**
     * Check if a value exists in this resource
     *
     * @param string $key The key of the value
     *
     * @return bool false
     */
    public function has($key)
    {
        return false;
    }

    /**
     * Returns the keys of all setted values in this resource
     *
     * @return array<string> Keys of all setted values
     */
    public function getKeys()
    {
        return [];
    }

    /**
     * Get a value by the key of this identifier
     *
     * @param string $key The key of the value
     */
    public function get($key): void
    {
        throw new AccessException('A ResourceNull has no values.');
    }
}
