<?php

namespace System;

class Cookie
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Cookies Path
     *
     * @var string
     */
    private $path = '/';

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        // we will get the path from SCRIPT_NAME index from _SERVER variable
        // we will remove or just get the directory of the script name by removing
        // the file name from it => we will remove index.php
        $this->path = dirname($this->app->request->server('SCRIPT_NAME')) ?: '/';
    }

    /**
     * Set New Value to Cookie
     *
     * @param string $key
     * @param mixed $value
     * @param int $hours
     * @return void
     */
    public function set($key, $value, $hours = 1800)
    {

        if (is_array($value)) $value = json_encode($value);
        // here we will see if the hours variable equals -1
        // then it means we will remove the key from cookies
        // otherwise, we will just add our normal time
        $expireTime = $hours == -1 ? -1 : time() + $hours * 3600;

        //        key   value   expire time  path      domain secure httpOnly

        setcookie($key, $value, $expireTime, $this->path, '', false, true);
    }

    /**
     * Get Value from Cookies by the given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $isArr = false, $default = null)
    {
        $value = array_get($_COOKIE, $key, $default);

        return $isArr ? json_decode($value, true) : $value;
    }

    /**
     * Determine if the Cookies has the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * Remove the given key from Cookie
     *
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        $this->set($key, null, -1);

        unset($_COOKIE[$key]);
    }

    /**
     * Get all Cookies data
     *
     * @return array
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * Destroy Cookie
     *
     * @return void
     */
    public function destroy()
    {
        foreach (array_keys($this->all()) as $key) {
            $this->remove($key);
        }

        unset($_COOKIE);
    }
}
