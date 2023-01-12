<?php

namespace System;

use Plugins\PHPMailer\PHPMailer;
use Plugins\PHPMailer\SMTP;
use Plugins\PHPMailer\Exception;

class Messaging
{

  /**
   * application object
   *
   * @var \System\Application
   */
  private $app;

  /**
   * site email data
   *
   * @var String
   */
  private $emailConfig;

  /**
   * Constructor
   *
   * @var \System\Application
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
    $this->emailConfig = $this->app->file->import('/config/email.php');
  }

  /**
   * Send email to the given email address
   *
   * @param string $to
   * @param string $subject
   * @param string $message
   * @return bool
   */
  public function email($to, $subject, $message)
  {
    $mail = new PHPMailer();

    try {
      $mail->isSMTP();
      $mail->SMTPDebug  = SMTP::DEBUG_OFF;
      $mail->Host       = $this->emailConfig['host'];
      $mail->Port       = $this->emailConfig['port'];
      $mail->SMTPAuth   = true;
      $mail->Username   = $this->emailConfig['username'];
      $mail->Password   = $this->emailConfig['password'];
      // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->setFrom($this->emailConfig['address']);
      $mail->addReplyTo($this->emailConfig['address']);
      $mail->addAddress($to);
      $mail->Subject    = $subject;
      $mail->Body       = $message;

      return  $mail->send();
    } catch (Exception $e) {
      pred($e);
      return false;
    }
  }
}
