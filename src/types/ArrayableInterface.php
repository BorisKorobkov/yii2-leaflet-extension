<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link https://2amigos.us
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */
/**
 * @copyright Copyright (c) 2026 Boris Korobkov
 * @link https://github.com/BorisKorobkov
 * @license https://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace boriskorobkov\leaflet\types;

/**
 * Arrayable should be implemented by classes that need to be represented in array format.
 *
 * @package boriskorobkov\leaflet\types
 */
interface ArrayableInterface
{
    /**
     * Converts the object into an array.
     *
     * @param bool $encode whether to return the array json_encoded or raw
     *
     * @return array the array representation of this object
     */
    public function toArray($encode = false): mixed;
}
