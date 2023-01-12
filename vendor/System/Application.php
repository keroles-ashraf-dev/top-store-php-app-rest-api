<?php

namespace System;

use Closure;

class Application
{

  /**
   * Container
   *
   * @var array
   */
  private $container = [];

  /**
   * application object
   *
   * @var System\Application
   */
  private static $instance;

  /**
   * Constructor
   *
   * @param \System\File $file
   */
  private function __construct(File $file)
  {
    $this->share('file', $file);
    $this->registerClasses();
    static::$instance = $this;
    $this->loadHelpers();
  }

  /**
   * get application instance
   * 
   * @param \System\File $file
   * @return \System\Application
   */
  public static function getInstance($file = null)
  {
    if (is_null(static::$instance)) {
      static::$instance = new static($file);
    }
    return static::$instance;
  }

  /**
   * run the application
   *
   * @return void
   */
  public function run()
  {
    $this->session->start();

    $this->request->prepareUrl();
    $this->file->import('App/index.php');

    list($controller, $method, $arguments) = $this->route->getProperRoute();

    $this->route->callMiddleWares();
    $this->route->cleanMiddleWares();

    $output = (string) $this->load->action($controller, $method, $arguments);

    $this->response->setOutput($output);

    $this->response->send();
  }

  /**
   * register classes in spl auto load register
   *
   * @return void
   */
  private function registerClasses()
  {
    spl_autoload_register([$this, 'load']);
  }

  /**
   * load class through auto loading
   *
   * @param string $class
   * @return void
   */
  public function load($class)
  {

    if (strpos($class, 'App') === 0) {
      $file = $class . '.php';
    } else {
      $file = 'vendor/' . $class . '.php';
    }

    if ($this->file->exists($file)) {
      $this->file->import($file);
    }
  }

  /**
   * load helpers file
   *
   * @return void
   */
  private function loadHelpers()
  {
    $this->file->import('vendor/helpers.php');
  }

  /**
   * get shared value
   *
   * @param string $key
   * @return mixed
   */
  public function get($key)
  {
    if (!$this->isSharing($key)) {

      if ($this->isCoreAlias($key)) {

        $this->share($key, $this->createNewCoreObject($key));
      } else {
        die('<br>' . $key .  '</br>' . 'not found in application container');
      }
    }

    return $this->container[$key];
  }

  /**
   * determine if the given key is shared through application
   *
   * @param string $key
   * @return bool
   */
  public function isSharing($key)
  {
    return isset($this->container[$key]);
  }

  /**
   * Share the give key|value through application 
   *
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  public function share($key, $value)
  {
    if ($value instanceof Closure) {
      $value = call_user_func($value, $this);
    }

    $this->container[$key] = $value;
  }

  /**
   * Share the give key|value through application 
   *
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  private function createNewCoreObject($alias)
  {
    $coreClasses = $this->coreClasses();
    $object = $coreClasses[$alias];
    return new $object($this);
  }

  /**
   * determine if class aliass is found in core classes aliasses
   *
   * @param string $alias
   * @return bool
   */
  private function isCoreAlias($alias)
  {
    $coreClasses = $this->coreClasses();
    return isset($coreClasses[$alias]);
  }

  private function coreClasses()
  {
    return [
      'request'       => 'System\\Http\\Request',
      'response'      => 'System\\Http\\Response',
      'session'       => 'System\\Session',
      'route'         => 'System\\Route',
      'cookie'        => 'System\\Cookie',
      'load'          => 'System\\Loader',
      'html'          => 'System\\Html',
      'db'            => 'System\\Database',
      'view'          => 'System\\View\\ViewFactory',
      'url'           => 'System\\Url',
      'validator'     => 'System\\Validation',
      'security'      => 'System\\Security',
      'messaging'     => 'System\\Messaging',
      'pagination'    => 'System\\Pagination',
      'api'           => 'System\\RestApi',
    ];
  }

  /**
   * get shared value dynamically
   *
   * @param string $key
   * @return mixed
   */
  public function __get($key)
  {

    return $this->get($key);
  }
}
