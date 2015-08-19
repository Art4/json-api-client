<?php

namespace Art4\JsonApiClient\Resource;

/**
 * Resource Interface
 */
interface ResourceInterface
{
	public function isNull();

	public function isIdentifier();

	public function isItem();

	public function isCollection();
}
