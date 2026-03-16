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
    public static function start()
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
    public function set($name = null, $value = null)
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
    public function get($name = null)
    {
        self::start();

        return $this->has($name) ? $_SESSION[$name] : false;
    }

    /**
     * Delete Session.
     *
     * @param string $name
     * @return bool
     */
    public function delete($name = null)
    {
        self::start();

        if (!$this->has($name)) {
            return false;
        }

        unset($_SESSION[$name]);

        return true;
    }

    /**
     * Check Session.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        self::start();

        return (!empty($name) && isset($_SESSION[$name]));
    }
}
