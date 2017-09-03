<?php
/**
 * This file is part of Vcampitelli\Slim\View
 */

namespace Vcampitelli\Slim\View\Helper;

// use Curseduca\Tools\Core\Helper\IteratorAbstract;

/**
 * Base container to handle simple collections
 *
 * @since 0.1.0
 */
class BaseContainer
{
    /**
     * Helper
     *
     * @var array
     */
    protected $data = [];

    /**
     * Adds item to data
     *
     * @param  string $value Item to be added
     *
     * @return self
     */
    public function add($value)
    {
        $this->data[] = $value;
        return $this;
    }

    /**
     * Sets item to specified index key
     *
     * @param  string $key   Index key
     * @param  string $value Sheet file
     *
     * @return self
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
}
