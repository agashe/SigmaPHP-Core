<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Exceptions\InvalidArgumentException;
use SigmaPHP\Core\Interfaces\Http\SessionInterface;

/**
 * Session Class
 */
class Session implements SessionInterface
{
    /**
     * Start new session.
     * 
     * @return void
     */
    final public static function start()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
    }

    /**
     * Create Session.
     * 
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    final public function set($name = null, $value = null)
    {
        if (empty($name) || empty($value)) {
            $property = empty($name)? 'name': 'value';
            
            throw new InvalidArgumentException(
                "Error : Create new Session , {$property} is empty"
            );
            
            return false;
        }

        self::start();

        $_SESSION[$name] = $value;

        return true;
    }

    /**
     * Get Session Value.
     * 
     * @param string $name
     * @return mixed
     */
    final public function get($name = null)
    {
        self::start();

        if (empty($name) || !isset($_SESSION[$name])) {
            return false;
        }
        
        return  $_SESSION[$name];        
    }

    /**
     * Delete Session.
     * 
     * @param string $name
     * @return bool
     */
    final public function delete($name = null)
    {
        self::start();

        if (empty($name) || !isset($_SESSION[$name])) {
            return false;
        }

        unset($_SESSION[$name]);

        return true;
    }
}