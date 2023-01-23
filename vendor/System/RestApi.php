<?php

namespace System;

class RestApi
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
   * @param \System\Application
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   * request headers
   * 
   * @return array $headers
   */
  function getHeaders()
  {
    $headers = getallheaders();

    if ($headers == false) return [];

    return $headers;
  }

  /**
   * response headers
   * 
   * @param array $headers
   * 
   * @return $this
   */
  function setHeaders($headers = null)
  {
    // remover any pervious set headers
    header_remove();

    header('Content-Type: application/json');

    if ($headers) {
      foreach ($headers as $header) {
        header($header);
      }
    }

    return $this;
  }

  /**
   * success request
   * 
   * @param array $data
   * @return void
   */
  function success($data = [])
  {
    // success code
    http_response_code(200);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * bad request
   * 
   * @param array $data
   * @return void
   */
  function badRequest($data = [])
  {
    // bad request code
    http_response_code(400);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * unauthorized request
   * 
   * @param array $data
   * @return void
   */
  function unauthorized($data = [])
  {
    // unauthorized code
    http_response_code(401);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * forbidden request
   * 
   * @param array $data
   * @return void
   */
  function forbidden($data = [])
  {
    // unauthorized code
    http_response_code(403);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * not found resource
   * 
   * @param array $data
   * @return void
   */
  function notFound($data = [])
  {
    // not found code
    http_response_code(404);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * internal server error
   * 
   * @param array $data
   * @return void
   */
  function internalError($data = [])
  {
    // internal error code
    http_response_code(500);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }

  /**
   * service unavailable error
   * 
   * @param array $data
   * @return void
   */
  function serviceUnavailable($data = [])
  {
    // internal error code
    http_response_code(503);

    if (!empty($data)) {
      echo json_encode($data);
    }

    exit;
  }
}
