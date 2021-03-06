<?php

/*
 * This file is part of the webmozart/key-value-store package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\KeyValueStore;

use Webmozart\KeyValueStore\Api\CountableStore;
use Webmozart\KeyValueStore\Api\NoSuchKeyException;
use Webmozart\KeyValueStore\Api\SortableStore;

/**
 * A key-value store that does nothing.
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class NullStore implements SortableStore, CountableStore
{
    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrFail($key)
    {
        throw NoSuchKeyException::forKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple(array $keys, $default = null)
    {
        return array_fill_keys($keys, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getMultipleOrFail(array $keys)
    {
        throw NoSuchKeyException::forKeys($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function sort($flags = SORT_REGULAR)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return 0;
    }
}
