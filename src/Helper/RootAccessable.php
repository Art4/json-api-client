<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Helper;

use Art4\JsonApiClient\Accessable;

/**
 * RootAccessable
 *
 * @internal
 */
final class RootAccessable implements Accessable
{
    use AccessableTrait;
}
