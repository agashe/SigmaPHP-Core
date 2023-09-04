<?php

namespace SigmaPHP\Core\Http;

use SigmaPHP\Core\Exceptions\InvalidArgumentException;
use SigmaPHP\Core\Interfaces\Http\CookieInterface;

/**
 * Cookie Class
 */
class Cookie implements CookieInterface
{
    /**
     * Create Cookie.
     * 
     * @param string $name
     * @param mixed $value
     * @param int $expireAt
     * @param array $options
     * @return bool
     */
    final public function set(
        $name = null,
        $value = null,
        $expireAt = 0,
        $options = []
    ) {
        if (empty($name) || empty($value)) {
            $property = empty($name)? 'name': 'value';
            
            throw new InvalidArgumentException(
                "Error : Create new cookie , {$property} is empty"
            );
            
            return false;
        }

        return setcookie($name, $value, ([
            'expires' => $expireAt
        ] + $options));
    }

    /**
     * Get Cookie Value.
     * 
     * @param string $name
     * @return mixed
     */
    final public function get($name = null)
    {
        return (!empty($name) && isset($_COOKIE[$name])) ?
            $_COOKIE[$name] : false;        
    }

    /**
     * Delete Cookie.
     * 
     * @param string $name
     * @return bool
     */
    final public function delete($name = null)
    {
        if (empty($name) || !isset($_COOKIE[$name])) {
            return false;
        }

        return setcookie($name, '', [
            'expires' => (time() - 3600)
        ]);
    }
}