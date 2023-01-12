<?php

namespace System;

use Exception;

class Security
{

  /**
   * application object
   *
   * @var \System\Application
   */
  private $app;

  /**
   * the remaining time in minutes to send email
   *
   * @var int
   */
  private $remainingTimeToSendEmail;

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
   * check if requested route needs login
   * 
   * @return bool
   */
  function isLoginNeeded($requestedUrl)
  {
    $loginNeededUrls = ['/profile', '/cart', '/payment', '/support'];

    foreach ($loginNeededUrls as $url) {
      if (str_starts_with($requestedUrl, $url)) return true;
    }

    return false;
  }

  /**
   * check if requested rest api route needs auth token
   * 
   * @return bool
   */
  function isAuthTokenNeeded($requestedUrl)
  {
    $tokenNeededUris = ['/api/v1/user', '/api/v1/address', '/api/v1/addresses'];

    foreach ($tokenNeededUris as $uri) {
      if (str_starts_with($requestedUrl, $uri)) return true;
    }

    return false;
  }

  /**
   * check count and time of last emails sent to check if valid to sent email
   * @return bool
   */
  public function isValidToSendEmail()
  {
    [$count, $time, $differenceInMinutes] = $this->getSentEmailsInfoFromCookie();

    if ($count == 0) {
      return true;
    }

    // if sent emails count equals 2 then wait for 10 minutes
    if ($count == 2 && $differenceInMinutes < 10) {
      $this->remainingTimeToSendEmail = ceil(10 - $differenceInMinutes);
      return false;
    }

    // if sent emails count grater than 2 then wait for 1440 minutes(a day)
    if ($count > 2 && $differenceInMinutes < 1440) {
      $this->remainingTimeToSendEmail = ceil(1440 - $differenceInMinutes);
      return false;
    }

    return true;
  }

  /**
   * return remaining time in minutes to send email 
   * 
   * @return int
   */
  public function getRemainingTimeToSendEmail()
  {
    return $this->remainingTimeToSendEmail;
  }

  /**
   * set sent emails in cookies
   * 
   * @return String
   */
  public function setSentEmailsInfoToCookie()
  {
    [$count, $time, $differenceInMinutes] = $this->getSentEmailsInfoFromCookie();
    $sentEmailsInfo['count'] = $count + 1;
    $sentEmailsInfo['time'] = time();
    $this->app->cookie->set('sent-emails-info', $sentEmailsInfo);
  }

  /**
   * get sent emails from cookies
   * 
   * @return array
   */
  private function getSentEmailsInfoFromCookie()
  {
    $info = $this->app->cookie->get('sent-emails-info', true);
    $count = 0;
    $time = time();
    $differenceInMinutes = null;

    if ($info) {
      $count = intval($info['count']);
      $time = intval($info['time']);
      $differenceInMinutes = (time() - $time) / 60;
    }

    return [$count, $time, $differenceInMinutes];
  }

  /**
   * check form token if valid by given form key
   * 
   * @param String $key
   * @return bool
   */
  public function isUserInputTokenValid($key)
  {
    $userInputToken = $this->app->request->fileGetContents($key);
    $token = $this->app->session->get($key);

    return $this->isIdentical($token, $userInputToken);
  }

  /**
   * Generate new token then save it to session or cookie by given key then return it 
   *
   * @param String $key
   * @return string
   */
  function setNewTokenToStorageAndReturnIt($key, $storage)
  {
    $token = $this->generateToken();

    $this->app->$storage->set($key, $token);

    return $token;
  }

  /**
   * Generate new otp then save it to session or cookie by given key then return it 
   *
   * @param String $key
   * @return string
   */
  function setNewOtpToStorageAndReturnIt($key, $storage)
  {
    $otp = '123'; //$this->generateOTP();
    $this->app->$storage->set($key, $otp);

    return $otp;
  }

  /**
   * Generate Token
   *
   * @return string
   */
  public function generateToken()
  {
    $token = bin2hex(random_bytes(32));
    return $token;
  }

  /**
   * Generate OTP 6 characters
   *
   * @return string
   */
  public function generateOTP()
  {
    $otp = random_int(100000, 999999);
    return $otp;
  }

  /**
   * check if passed values are identical
   *
   * @param String $firstValue
   * @param String $secondValue
   * @return bool
   */
  function isIdentical($firstValue, $secondValue)
  {
    return hash_equals(strval($firstValue), strval($secondValue));
  }

  /**
   * get user country by ip
   *
   * @return String
   */
  public function getUserCountry()
  {
    $data = @file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']);
    $json = unserialize($data);
    $country = $json['geoplugin_countryName'] ?? 'unlocated';

    return $country;
  }
}
