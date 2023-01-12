<?php

namespace System;

use Socket;

abstract class WebSocket
{
	/**
	 * websocket host 
	 *
	 * @var String
	 */
	private $host;

	/**
	 * websocket port 
	 *
	 * @var String
	 */
	private $port;

	/**
	 * listening socket
	 *
	 * @var Socket
	 */
	private $socket;

	/**
	 * connected clients
	 *
	 * @var array
	 */
	private $clients;

	/**
	 * constructor
	 *
	 */
	public function __construct($host, $port)
	{
		$this->host = $host;
		$this->port = $port;
	}

	/**
	 * destructor
	 *
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * run websocket
	 *
	 */
	public function run()
	{
		// allow the script to hang around waiting for connections
		set_time_limit(0);
		//Create TCP/IP stream socket
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		//reuseable port
		socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
		//bind socket to specified host
		socket_bind($this->socket, $this->host, $this->port);
		//listen to port
		socket_listen($this->socket);
		//add listening socket to the list  | listening socket has no id so we pass zero instead
		$this->clients = array(['socket' => $this->socket(), 'id' => '0']);
		// listening to socket
		$this->listening();
	}

	/**
	 * listen to websocket
	 *
	 */
	private function listening()
	{
		while (true) {

			// create a copy, so $clients doesn't get modified by socket_select()
			foreach ($this->clients as $client) {
				$changedClients[] = $client['socket'];
			}

			// get a list of all the clients that have data to be read from
			// if there are no clients with data, go to next iteration
			if (socket_select($changedClients, $null, $null, 0, 10) < 1) continue;

			//check for new socket
			if (in_array($this->socket, $changedClients)) {

				$newSocket = socket_accept($this->socket); //accept new socket
				$newSocketData = $this->getSocketData($newSocket); // get new socket data

				$this->onConnect($newSocket, $newSocketData);

				// remove the listening socket from the $changedClients array
				$listeningSocketIndex = array_search($this->socket, $changedClients);
				unset($changedClients[$listeningSocketIndex]);
			}

			// loop through all the clients that have data to read from
			foreach ($changedClients as $client) {

				//check for any incoming data
				while (socket_recv($client, $data, 1024, 0) >= 1) {

					$unmaskedData = $this->unmask($data); //unmask received data

					$this->onMessage($client, $unmaskedData);

					break 2; //exist this loop
				}

				// socket_read while show errors when the client is disconnected, so silence the error messages
				$data = @socket_read($client, 1024, PHP_NORMAL_READ);

				// check if the client is disconnected
				if ($data === false) {

					$this->onDisconnect($client);

					continue; // continue to the next client to read from, if any
				}
			}
		}

		$this->close();
	}

	/**
	 * encode message for transfer to client
	 *
	 * @var json data
	 * 
	 * @return String
	 */
	private function mask($data)
	{
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($data);

		if ($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif ($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif ($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header . $data;
	}

	/**
	 * unmask incoming message
	 *
	 * @var json data
	 * 
	 * @return json
	 */
	private function unmask($maskedData)
	{
		$length = ord($maskedData[1]) & 127;
		if ($length == 126) {
			$masks = substr($maskedData, 4, 4);
			$data = substr($maskedData, 8);
		} elseif ($length == 127) {
			$masks = substr($maskedData, 10, 4);
			$data = substr($maskedData, 14);
		} else {
			$masks = substr($maskedData, 2, 4);
			$data = substr($maskedData, 6);
		}
		$unmaskedData = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$unmaskedData .= $data[$i] ^ $masks[$i % 4];
		}
		return $unmaskedData;
	}

	/**
	 * perform handshaking to new socket
	 *
	 * @var Socket socket
	 * @var String connection id
	 */
	private function handshaking($socket, $connectionId)
	{
		$secKey = $connectionId;
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		//hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
			"Upgrade: websocket\r\n" .
			"Connection: Upgrade\r\n" .
			"WebSocket-Origin: $this->host\r\n" .
			"WebSocket-Location: ws://$this->host:$this->port/store/websocket/chat.php\r\n" .
			"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($socket, $upgrade, strlen($upgrade));
	}

	/**
	 * add new client
	 *
	 * @var Socket socket
	 * @var String connection id
	 */
	public function addNewClient($socket, $connectionId)
	{
		// perform handshaking to new socket
		$this->handshaking($socket, $connectionId);
		// add new client
		$this->clients[] = ['socket' => $socket, 'id' => $connectionId];
	}

		/**
	 * remove client
	 *
	 * @var Socket socket
	 */
	public function removeClient($socket)
	{
		$count = count($this->clients);

		for ($i = 0; $i < $count; $i++) {

			if (isset($this->clients[$i]) && $this->clients[$i]['socket'] == $socket) {
				unset($this->clients[$i]);
				break;
			}
		}
	}

	/**
	 * socket getter
	 */
	public function socket()
	{
		return $this->socket;
	}

	/**
	 * clients getter
	 *
	 */
	public function clients()
	{
		return $this->clients;
	}

	/**
	 * get passed socket data
	 *
	 * @var Socket socket
	 * @return array data
	 */
	public function getSocketData($socket)
	{
		$socketData = socket_read($socket, 1024); //read data sent by the socket

		// check if no data
		if ($socketData === false) return [];

		$data = array();

		$lines = preg_split("/\r\n/", $socketData);

		foreach ($lines as $line) {

			$line = chop($line);

			if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
				$data[$matches[1]] = $matches[2];
			}
		}

		return $data;
	}

	/**
	 * send message to client
	 *
	 * @var Socket socket
	 * @var array data
	 */
	public function sendMessage($socket, $data)
	{
		$sendingData = $this->mask(json_encode($data));
		socket_write($socket, $sendingData, strlen($sendingData));
	}

	/**
	 * close the listening socket
	 */
	public function close()
	{
		socket_close($this->socket);
	}

	/**
	 * new client connected event
	 * 
	 * @var Socket socket
	 * @var array socketData
	 */
	abstract public function onConnect($newSocket, $newSocketData);

	/**
	 * new message received event
	 * 
	 * @var Socket from
	 * @var json mag
	 */
	abstract public function onMessage($from, $msg);

	/**
	 * client disconnected event
	 * 
	 * @var Socket client
	 */
	abstract public function onDisconnect($client);
}
