<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

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

@trigger_error(__NAMESPACE__ . '\RelationshipLink is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\V1\RelationshipLink instead', E_USER_DEPRECATED);

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\ForwardCompatibility\AbstractElement;

/**
 * Relationship Link Object
 *
 * @deprecated RelationshipLink is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\V1\RelationshipLink instead.
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * links: a links object containing at least one of the following:
 * - self: a link for the relationship itself (a "relationship link"). This link allows
 *   the client to directly manipulate the relationship. For example, it would allow a
 *   client to remove an author from an article without deleting the people resource itself.
 * - related: a related resource link
 *
 * A relationship object that represents a to-many relationship MAY also contain pagination
 * links under the links member, as described below.
 */
final class RelationshipLink extends AbstractElement implements RelationshipLinkInterface
{
    /**
     * Get the represented Element name for the factory
     *
     * @return string the element name
     */
    protected function getElementNameForFactory()
    {
        return 'RelationshipLink';
    }

    /**
     * Get a value by the key of this object
     *
     * @param string $key The key of the value
     *
     * @return mixed The value
     */
    public function get($key)
    {
        try {
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }
}
