<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient;

/**
 * Element Interface
 */
interface Element
{
    /**
     * Sets the manager and parent
     *
     * @param mixed $data The data for this Element
     */
    public function __construct($data, Manager $manager, Accessable $parent);
}
