<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Input;

use Art4\JsonApiClient\Exception\InputException;

/**
 * Handles a http Request body as string
 */
final class RequestStringInput implements Input, RequestInput
{
    use StringInputTrait;

    private string $rawString;

    /**
     * Set the input
     *
     * @param string $string
     *
     * @throws InputException if $string is not a string
     */
    public function __construct($string)
    {
        $this->rawString = $this->prepareString($string);
    }

    /**
     * Get the input as simple object
     *
     * This should be a native PHP stdClass object, so Manager could
     * iterate over all public attributes
     *
     * @throws InputException if something went wrong with the input
     */
    public function getAsObject(): \stdClass
    {
        $data = $this->decodeJson($this->rawString);

        if (! $data instanceof \stdClass) {
            throw new InputException('JSON must contain an object (e.g. `{}`).');
        }

        return $data;
    }
}
