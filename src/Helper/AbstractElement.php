<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

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

    private Manager $manager;

    private Accessable $parent;

    /**
     * Sets the manager and parent
     *
     * @param mixed $data The data for this Element
     */
    public function __construct(mixed $data, Manager $manager, Accessable $parent)
    {
        $this->manager = $manager;
        $this->parent = $parent;

        $this->parse($data);
    }

    /**
     * Returns the Manager
     */
    protected function getManager(): Manager
    {
        return $this->manager;
    }

    /**
     * Get the parent
     */
    protected function getParent(): Accessable
    {
        return $this->parent;
    }

    /**
     * Create an element
     */
    protected function create(string $name, mixed $data): Accessable
    {
        return $this->getManager()->getFactory()->make(
            $name,
            [$data, $this->getManager(), $this]
        );
    }

    /**
     * Parse the data
     */
    abstract protected function parse(mixed $data): void;
}
