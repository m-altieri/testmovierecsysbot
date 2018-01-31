<?php

function sendChatAction($chat_id, $action) {
	
	$utils = require '/app/recsysbot/facebook/utils.php';
	
	$url = $utils->sendMessageURI();
	
	$json = [
		'messaging_type' => 'RESPONSE',
		'recipient' => [
				'id' => $chat_id
		],
		'sender_action' => $action
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
	curl_setopt($ch, CURLOPT_HEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	file_put_contents("php://stderr", "\nResult: " . $result . PHP_EOL);
}