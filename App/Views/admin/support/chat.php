<div class="chat-page">
	<div id="js-chats-list-container" class="chats-list-container">
		<?php
		if (!empty($chatsList)) {
			foreach ($chatsList as $chat) {
				$html = '
				<div class="chat-card ' . (($channelId == $chat->channel_id) ? 'active' : '') . '" data-receiver="?id" data-channel="?channelId">
					<a href="?overViewUrl">
					<div class="data">
					<span>?name</span>
					<span>?email</span>
					</div>
					<i class="fa-solid fa-chevron-right"></i>
					</a>
				</div>
				';

				$values = [
					'id' => $chat->user_id,
					'channelId' => $chat->channel_id,
					'name' => $chat->first_name . ' ' . $chat->last_name,
					'email' => $chat->email,
					'overViewUrl' => url('admin/support/chat?channelId=') . $chat->channel_id,
				];
				echo inject_html($html, $values);
			}
		}
		?>
	</div>
	<div id="js-messages-list-container" class="messages-list-container">
		<?php
		if (!empty($messages)) {
			foreach ($messages as $message) {
				$html = '
					<div class="row ' . (($adminId == $message->sender_id) ? 'my-message' : '') . '">
						<div class="message-card ' . (($adminId == $message->sender_id) ? 'my-message' : '') . '">
							<span class="message-content">?message</span>
							<span class="message-date">?date</span>
						</div>
					</div>
				';
				$values = [
					'message' => $message->message,
					'date' => date('H:m d/m/Y', $message->created),
				];
				echo inject_html($html, $values);
			}
		}
		?>
	</div>
	<div class="new-message-container">
		<?php if (!empty($channelId)) : ?>
			<textarea id="js-message-content" class="message-content" placeholder="Message..."></textarea>
			<button id="js-send-message-btn" class="message-send" data-admin="<?php echo $adminId ?>">
				<i class="fa-solid fa-paper-plane"></i>
			</button>
		<?php endif; ?>
	</div>
</div>