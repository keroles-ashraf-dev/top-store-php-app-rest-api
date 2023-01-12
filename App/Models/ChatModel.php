<?php

namespace App\Models;

use System\Model;

class ChatModel extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	private $channelsTable = 'chat_channels';

	/**
	 * Table name
	 *
	 * @var string
	 */
	private $messagesTable = 'chat_messages';

	/**
	 * Create New channel
	 *
	 * @return void
	 */
	public function createChannel($userId)
	{
		$this
			->data('user_id', $userId)
			->insert($this->channelsTable);
	}

	/**
	 * Get user chat channels
	 *
	 * @var int user id
	 * @return array
	 */
	public function getChatList($userId = null)
	{
		if ($userId == null) {
			
			$chatList = $this->select('c.id channel_id, c.user_id, u.first_name, u.last_name, u.email, m.created')
				->from($this->channelsTable . ' c')
				->join('LEFT JOIN users u ON c.user_id = u.id')
				->join('LEFT JOIN chat_messages m ON m.created = (SELECT m.created FROM chat_messages m WHERE m.chat_channel_id = c.id ORDER BY m.created LIMIT 1)')
				->orderBy('m.created')
				->where('u.role != ?', 'admin')
				->fetchAll();

			return $chatList;
		}

		return $this->select('*')
			->from($this->channelsTable)
			->where('user_id = ?', $userId)
			->fetchAll();
	}

	/**
	 * Get messages of passed chat channel id
	 *
	 * @var int channel id
	 * @return array
	 */
	public function getMessages($channelId)
	{
		return $this->select('*')
			->from($this->messagesTable)
			->where('chat_channel_id = ?', $channelId)
			->orderBy('created')
			->fetchAll();
	}
}
