<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Serializer;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\V1\ResourceNull;

final class ArraySerializer implements Serializer
{
    /** @var array<string, mixed> */
    private array $config = [
        'recursive' => false,
    ];

    /**
     * Setup the serializer
     *
     * @param array<string, mixed> $params
     */
    public function __construct(array $params = [])
    {
        foreach ($this->config as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->config[$key] = $params[$key];
            }
        }
    }

    /**
     * Convert data in an array
     *
     * @return array<int|string, mixed>|null
     */
    public function serialize(Accessable $data): ?array
    {
        $fullArray = (bool) $this->config['recursive'];

        if ($data instanceof ResourceNull) {
            return null;
        }

        $array = [];

        foreach ($data->getKeys() as $key) {
            $value = $data->get($key);

            if ($fullArray) {
                $array[$key] = $this->objectTransform($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Transforms objects to arrays
     *
     * @param mixed $val
     *
     * @return mixed
     */
    private function objectTransform($val)
    {
        if (!is_object($val)) {
            return $val;
        } elseif ($val instanceof Accessable) {
            return $this->serialize($val);
        } else {
            // Fallback for stdClass objects
            $jsonVal = json_encode($val, JSON_THROW_ON_ERROR);

            return json_decode($jsonVal, true);
        }
    }
}
