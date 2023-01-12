<?php

use \System\WebSocket;
use \System\Database;

class Chat extends WebSocket
{
	/**
	 * database instance
	 *
	 * @var Database
	 */
	private $db;

	/**
	 * constructor
	 *
	 */
	public function __construct($app, $host, $port)
	{
		parent::__construct($host, $port);
		$this->db = $app->db;
	}

	/**
	 * @override
	 * new client connected event
	 * 
	 * @var Socket socket
	 * @var array socketData
	 */
	public function onConnect($newSocket, $newSocketData)
	{
		$connectionId = $newSocketData['Sec-WebSocket-Key'];
		$userId = $this->getClientUserId($newSocketData);

		if (empty($userId)) return;

		// update user chat socket id
		$this->db
			->data('chat_socket_id', $connectionId)
			->where('id = ?', $userId)
			->update('users');

		//add socket to clients array
		$this->addNewClient($newSocket, $connectionId);
	}

	/**
	 * @override
	 * new message received event
	 * 
	 * @var Socket from
	 * @var json mag
	 */
	public function onMessage($from, $msg)
	{
		$msg = json_decode($msg, true);
		
		if (isset($msg['receiverId'])) {
			// message from admin, let's send it to user
			$this->handleAdminMsg($from, $msg);
		} else {
			// message from user, let's send it to admin
			$this->handleUserMsg($from, $msg);
		}
	}

	/**
	 * @override
	 * client disconnected event
	 * 
	 * @var Socket client
	 */
	public function onDisconnect($client)
	{
		$this->removeClient($client);
	}

	/**
	 * get user id from passed socket data
	 * 
	 * @var array socketData
	 * @return String userId
	 */
	private function getClientUserId($socketData)
	{
		$userId = '';

		$cookies = explode(';', $socketData['Cookie']);

		foreach ($cookies as $cookie) {

			if (str_starts_with($cookie, ' temp-id=')) {

				$userId = str_replace(' temp-id=', '', $cookie);
				break;
			}
		}
		return $userId;
	}

	/**
	 * insert message to db and send it to receiver user
	 * 
	 * @var Socket from
	 * @return String msg
	 */
	private function handleAdminMsg($from, $msg)
	{
		$senderId = isset($msg['senderId']) ? $msg['senderId'] : '';
		$receiverId = isset($msg['receiverId']) ? $msg['receiverId'] : '';
		$channelId = isset($msg['channelId']) ? $msg['channelId'] : '';
		$message = isset($msg['message']) ? $msg['message'] : '';
		$date = time();

		if (!is_numeric($senderId)) return;
		if (!is_numeric($receiverId)) return;
		if (!is_numeric($channelId)) return;
		if (!preg_match('/^[a-zA-Z0-9,-:\'" ]*$/', $message)) return;

		$this->db
			->data('chat_channel_id', $channelId)
			->data('sender_id', $senderId)
			->data('message', $message)
			->data('created', $date)
			->insert('chat_messages');

		$receiverConnId = $this->db->select('chat_socket_id')
			->from('users')
			->where('id = ?', $receiverId)
			->fetch()->chat_socket_id;

		if (empty($receiverConnId)) return;

		$data['senderId'] = $senderId;
		$data['message'] = $message;
		$data['date'] = date('H:m d/m/Y', $date);

		foreach ($this->clients() as $client) {

			if ($client['socket'] == $this->socket() || $client['socket'] == $from) continue;

			if ($client['id'] == $receiverConnId) {
				$this->sendMessage($client['socket'], $data);
				break;
			}
		}
	}

	/**
	 * insert message to db and send it to admin
	 * 
	 * @var Socket from
	 * @return String msg
	 */
	private function handleUserMsg($from, $msg)
	{
		$senderId = isset($msg['senderId']) ? $msg['senderId'] : '';
		$channelId = isset($msg['channelId']) ? $msg['channelId'] : '';
		$message = isset($msg['message']) ? $msg['message'] : '';
		$date = time();

		if (!is_numeric($senderId)) return;
		if (!is_numeric($channelId)) return;
		if (!preg_match('/^[a-zA-Z0-9,-:\'" ]*$/', $message)) return;

		$this->db
			->data('chat_channel_id', $channelId)
			->data('sender_id', $senderId)
			->data('message', $message)
			->data('created', $date)
			->insert('chat_messages');

		$admins = $this->db->select('chat_socket_id')
			->from('users')
			->where('role = ?', 'admin')
			->fetchAll();

		$data['senderId'] = $senderId;
		$data['message'] = $message;
		$data['date'] = date('H:m d/m/Y', $date);

		foreach ($this->clients() as $client) {

			if ($client['socket'] == $this->socket() || $client['socket'] == $from) continue;

			foreach ($admins as $admin) {
				if ($client['id'] == $admin->chat_socket_id) {
					$this->sendMessage($client['socket'], $data);
				}
			}
		}
	}
}
