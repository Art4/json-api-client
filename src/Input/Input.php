<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Input;

/**
 * Input Interface
 */
interface Input
{
    /**
     * Get the input as simple object
     *
     * @return-type-will-change \stdClass `\Art4\JsonApiClient\Input\Input::getAsObject()` will add `\stdClass` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * This should be a native PHP stdClass object, so Manager could
     * iterate over all public attributes
     *
     * @throws \Art4\JsonApiClient\Exception\InputException if something went wrong with the input
     *
     * @return \stdClass
     */
    public function getAsObject()/*: \stdClass */;
}
