<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Serializer;

use Art4\JsonApiClient\Accessable;

interface Serializer
{
    /**
     * Serialize data
     *
     * @return-type-will-change ?array `\Art4\JsonApiClient\Serializer\Serializer::serialize()` will add `?array` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @return array<string, mixed>|null
     */
    public function serialize(Accessable $data)/*: ?array */;
}
