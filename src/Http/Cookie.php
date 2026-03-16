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
     * @param string $value
     * @param int $expireAt
     * @param array $options
     * @return bool
     */
    public function set(
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
            'expires' => $expireAt,
            'path' => '/'
        ] + $options));
    }

    /**
     * Get Cookie Value.
     *
     * @param string $name
     * @return string|bool
     */
    public function get($name = null)
    {
        return $this->has($name) ? $_COOKIE[$name] : false;
    }

    /**
     * Delete Cookie.
     *
     * @param string $name
     * @return bool
     */
    public function delete($name = null)
    {
        if (!$this->has($name)) {
            return false;
        }

        return setcookie($name, '', [
            'expires' => (time() - 3600),
            'path' => '/'
        ]);
    }

    /**
     * Check Cookie.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return (!empty($name) && isset($_COOKIE[$name]));
    }
}
