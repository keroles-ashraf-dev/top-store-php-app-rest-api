<?php

require_once dirname(__DIR__, 2) . "/vendor/System/File.php";
require_once dirname(__DIR__, 2) . "/vendor/System/Application.php";
require_once dirname(__DIR__, 2) . "/vendor/System/WebSocket.php";
require_once dirname(__DIR__) . "/chat/Chat.php";

use \System\File;
use \System\Application;

$file = new File(dirname(__DIR__, 2));

$app = Application::getInstance($file);

$host = 'localhost';
$port = '8080';

$socket = new Chat($app, $host, $port);
$socket->run();
