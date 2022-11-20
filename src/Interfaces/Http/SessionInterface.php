<?php

namespace SigmaPHP\Core\Interfaces\Http;

/**
 * Session Interface
 */
interface SessionInterface
{
    /**
     * Create Session.
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function set($name, $value);

    /**
     * Get Session Value.
     * 
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * Delete Session.
     * 
     * @param string $name
     * @return bool
     */
    public function delete($name);
}