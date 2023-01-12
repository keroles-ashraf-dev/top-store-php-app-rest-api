<div class="chat-page">
	<div class="chats-list-container">
		<?php
		if (!empty($chatsList)) {
			foreach ($chatsList as $chat) {
				$html = '
				<div class="chat-card ' . (($channelId == $chat->id) ? 'active' : '') . '">
					<a href="?overViewUrl">
					<span>?name</span>
					<i class="fa-solid fa-chevron-right"></i>
					</a>
				</div>
				';

				$values = [
					'name' => 'support',
					'overViewUrl' => url('support/chat?channelId=') . $chat->id,
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
					<div class="row ' . (($userId == $message->sender_id) ? 'my-message' : '') . '">
						<div class="message-card ' . (($userId == $message->sender_id) ? 'my-message' : '') . '">
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
			<button id="js-send-message-btn" class="message-send" data-user="<?php echo $userId ?>" data-token="<?php echo $token ?>" data-channel="<?php echo $channelId ?>">
				<i class="fa-solid fa-paper-plane"></i>
			</button>
		<?php endif; ?>
	</div>
</div>