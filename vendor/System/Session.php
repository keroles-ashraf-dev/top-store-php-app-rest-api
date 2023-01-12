<?php

namespace System;

class Session
{

  /**
   * application object
   *
   * @var \System\Application
   */
  private $app;

  /**
   * Constructor
   *
   * @var \System\Application
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * start session
   * 
   * @return void
   */
  public function start()
  {
    ini_set('session.use_only_cookies', 1);
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  /**
   * set new value to session
   * 
   * @param mixed $key
   * @param mixed $value
   * @return void
   */
  public function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  /**
   * get value from session by the given key
   * 
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public function get($key, $default = null)
  {
    return array_get($_SESSION, $key, $default);
  }

  /**
   * determine if the session has the given key
   * 
   * @param string $key
   * @return bool
   */
  public function has($key)
  {
    return isset($_SESSION[$key]);
  }

  /**
   * remove the given key from session
   * 
   * @param string $key
   * @return void
   */
  public function remove($key)
  {
    unset($_SESSION[$key]);
  }

  /**
   * get value from session by the given key then remove it
   * 
   * @param string $key
   * @return mixed
   */
  public function pull($key)
  {
    $value = $this->get($key);
    $this->remove($key);
    return $value;
  }

  /**
   * get all session data
   * 
   * @return array
   */
  public function all()
  {
    return $_SESSION;
  }

  /**
   * destroy session except some values
   * 
   * @param mix $keys
   * @return void
   */
  public function destroy($keys = null)
  {

    if (!$keys) {
      session_destroy();
      unset($_SESSION);
      $this->start();
      return;
    }

    $sessions = [];

    if (is_array($keys)) {

      foreach ($keys as $key) {
        $sessions[$key] = $this->get($key);
      }
    } else {
      $sessions[$keys] = $this->get($keys);
    }

    session_destroy();
    unset($_SESSION);
    $this->start();

    if (is_array($sessions)) {

      foreach ($sessions as $key => $value) {
        $this->set($key, $value);
      }
    } else {
      $this->set($keys, $sessions[$keys]);
    }
  }
}
