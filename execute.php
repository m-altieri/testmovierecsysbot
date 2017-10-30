<?php
	
	/**
	 * @author Francesco Baccaro
	 */
	use GuzzleHttp\Client;
// 	use Telegram\Bot\Api;
	
	require 'vendor/autoload.php';
	$config = require __DIR__ . '/recsysbot/config/movierecsysbot-config.php';
	
	$webhookUrl = "https://testmovierecsysbot.herokuapp.com/execute.php";
	$myToken = "testmovierecsysbot";
	$accessToken = "EAAE0Lnad6ywBAGrnw7jHREnIc0CiZAuOpLV8iykP1WOFS8ykXFeoLm4340Js0ZCmZCdZAH6wwpkV6Lr5PoeWZA7b8miJP9vUjfWerd8rF9v95ORNWhdMPUFJmjZBhl4nxW0DDaCImlZBjgqYFpewFhHtVAiHkMBbgKgVVwT7E2lswZDZD";
	
	$verifyToken = $_REQUEST['hub_verify_token'];
	$challenge = $_REQUEST['hub_challenge'];
	if ($verifyToken === $myToken) {
		echo $_REQUEST['hub_challenge'];
		exit();
	}
	
// 	// ECHO
// 	$input = json_decode(file_get_contents('php://input'), true);
	
// 	$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
// 	$answer = $input['entry'][0]['messaging'][0]['message']['text'];
	
// 	$response = [
// 			'recipient' => [ 'id' => $senderId ],
// 			'message' => [ 'text' => $answer ]
// 	];
// 	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
// 	curl_setopt($ch, CURLOPT_POST, 1);
// 	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
// 	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
// 	curl_exec($ch);
// 	curl_close($ch);
	
	// richiedo tutti le funzioni, i messaggi di risposta e i servizi che mi servono per l'esecuzione
	foreach ( glob ( "recsysbot/classes/*.php" ) as $file ) {
		require $file;
	}
	foreach ( glob ( "recsysbot/functions/*.php" ) as $file ) {
		require $file;
	}
	foreach ( glob ( "recsysbot/keyboards/*.php" ) as $file ) {
		require $file;
	}
	foreach ( glob ( "recsysbot/messages/*.php" ) as $file ) {
		require $file;
	}
	foreach ( glob ( "recsysbot/replies/*.php" ) as $file ) {
		require $file;
	}
	foreach ( glob ( "recsysbot/restService/*.php" ) as $file ) {
		require $file;
	}
	
	// This is suggested from Guzzle
	date_default_timezone_set ( $config ['timezone'] );
	$token = $config ['token'];
	
// 	$telegram = new Api ( $token );
	
	// recupero il contenuto inviato da Telegram
	$content = file_get_contents("php://input");
// 	$content = $telegram->getWebhookUpdates ();
	
	// converto il contenuto da JSON ad array PHP
	$update = json_decode ( $content, true );
	
	// se la richiesta è null interrompo lo script
	if (! $update) {
		exit ();
	}
	
// 	$telegram->addCommand ( Recsysbot\Commands\HelpCommand::class );
// 	$telegram->addCommand ( Recsysbot\Commands\InfoCommand::class );
// 	$telegram->addCommand ( Recsysbot\Commands\ResetCommand::class );
// 	$telegram->addCommand ( Recsysbot\Commands\StartCommand::class );
	

	
	$message = $update["entry"][0]["messaging"][0];
	$messageId = $message["message"]["mid"];
	$chatId = $message["sender"]["id"]; //ID dell'utente, non della chat; non esiste su messenger
	$userInfo = json_decode(file_get_contents("https://graph.facebook.com/v2.6/" . $chatId . "?access_token=" . $accessToken), true);
	$firstname = $userInfo["first_name"];
	$lastname = $userInfo["last_name"];
	$username = ""; //Non viene restituito dalla chiamata
	$date = $update["entry"][0]["time"];
	$text = $message["message"]["text"];
	$globalDate = gmdate("Y-m-d\TH:i:s\Z", $date);
	
	/*
	 * Importanti:
	 * message
	 * messageId
	 * chatId
	 * firstname
	 * lastname
	 * username
	 * date
	 * text
	 * globalDate
	 * 
	 * Log:
	 * chatId
	 * firstName
	 * botName
	 * globalDate
	 * text
	 * PHP.EOL
	 */
	
	// Stampa nel log
	file_put_contents("php://stderr", "messageId: " . $messageId . "\nchatId: " . $chatId . "\nfirstname: " . $firstname . "\nlastname: " . $lastname . "\ndate: " . $date . "\ntext: " . $text . "\nglobalDate: " . $globalDate . PHP_EOL);
	
// 	// assegno alle seguenti variabili il contenuto ricevuto da Telegram
// 	$message = isset ( $update ['message'] ) ? $update ['message'] : "";
// 	$messageId = isset ( $message ['message_id'] ) ? $message ['message_id'] : "";
// 	$chatId = isset ( $message ['chat'] ['id'] ) ? $message ['chat'] ['id'] : "";
// 	$firstname = isset ( $message ['chat'] ['first_name'] ) ? $message ['chat'] ['first_name'] : "";
// 	$lastname = isset ( $message ['chat'] ['last_name'] ) ? $message ['chat'] ['last_name'] : "";
// 	$username = isset ( $message ['chat'] ['username'] ) ? $message ['chat'] ['username'] : "";
// 	$date = isset ( $message ['date'] ) ? $message ['date'] : "";
// 	$text = isset ( $message ['text'] ) ? $message ['text'] : "";
// 	$globalDate = gmdate ( "Y-m-d\TH:i:s\Z", $date );
	
// 	// gestisci edited_message, per evitare blocco del bot
// 	if ($chatId == "") {
// 		$message = isset ( $update ['edited_message'] ) ? $update ['edited_message'] : "";
// 		$messageId = isset ( $message ['message_id'] ) ? $message ['message_id'] : "";
// 		$chatId = isset ( $message ['chat'] ['id'] ) ? $message ['chat'] ['id'] : "";
// 		$firstname = isset ( $message ['chat'] ['first_name'] ) ? $message ['chat'] ['first_name'] : "";
// 		$lastname = isset ( $message ['chat'] ['last_name'] ) ? $message ['chat'] ['last_name'] : "";
// 		$username = isset ( $message ['chat'] ['username'] ) ? $message ['chat'] ['username'] : "";
// 		$date = isset ( $message ['date'] ) ? $message ['date'] : "";
// 		$text = isset ( $message ['text'] ) ? $message ['text'] : "";
// 		$globalDate = gmdate ( "Y-m-d\TH:i:s\Z", $date );
// 		file_put_contents ( "php://stderr", "edited_message execute.php - chatId: " . $chatId . " - update: " . print_r ( $update, true ) . PHP_EOL );
// 	}
// 	$botName = checkUserAndBotNameFunction ( $chatId, $firstname, $lastname, $username, $date );
	
	// pulisco il messaggio ricevuto togliendo eventuali spazi prima e dopo il testo
	$text = trim ( $text );
	
	// converto tutti i caratteri alfanumerici del messaggio in minuscolo
	$text = strtolower ( $text );
	
	try {
		$response = "";
		// gestisco il tipo di messaggio: testo
// 		if (isset ( $message ['text'] )) {
		if ( !isset ($message ['message']['attachments'][0]) ) { //Messenger
			
// 			if (($text == "/start")) {
// 				putUserDetail ( $chatId, $firstname, $lastname, $username );
// 				messageDispatcher ( $telegram, $chatId, $messageId, $date, $text, $firstname, $botName );
// 			} else {
// 				messageDispatcher ( $telegram, $chatId, $messageId, $date, $text, $firstname, $botName );
// 			}
			facebookSendMessage("Ho ricevuto del testo");
			file_put_contents("php://stderr", "funzione chiamata");
		} 
// // 		elseif (isset ( $message ['audio'] )) { //Telegram
// 		elseif ( $message ['message']['attachments'][0]['type'] === 'audio' ) { //Messenger
// 			$response = "I'm sorry. I received an audio message";
// // 			$telegram->sendMessage ( [ 
// // 					'chat_id' => $chatId,
// // 					'text' => $response 
// // 			] );
// 			//Stampa nel log
// 			file_put_contents("php://stderr", $response);
// // 		} elseif (isset ( $message ['document'] )) {
// 			elseif ( $message ['message']['attachments'][0]['type'] === 'file' ) { //Messenger
				
// 		}
// 			$response = "I'm sorry. I received a message document, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// // 		} elseif (isset ( $message ['photo'] )) {
// 			elseif ( $message ['message']['attachments'][0]['type'] === 'image' ) { //Messenger
				
// 		}
// 			$response = "I'm sorry. I received a message photo, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// 		} elseif (isset ( $message ['sticker'] )) {
// 			$response = "I'm sorry. I received a sticker message, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// // 		} elseif (isset ( $message ['video'] )) {
// 			elseif ( $message ['message']['attachments'][0]['type'] === 'video' ) { //Messenger
				
// 			$response = "I'm sorry. I received a video message, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// // 		} elseif (isset ( $message ['voice'] )) {
// 			$response = "I'm sorry. I received a voice message, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// // 		} elseif (isset ( $message ['contact'] )) {
// 			$response = "I'm sorry. I received a message contact, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// // 		} elseif (isset ( $message ['location'] )) {
// 			elseif ( $message ['message']['attachments'][0]['type'] === 'location' ) { //Messenger
				
// 			$response = "I'm sorry. I received a location message, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// 		} elseif (isset ( $message ['venue'] )) {
// 			$response = "I'm sorry. I received a venue, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// 		} else {
// 			$response = "I'm sorry. I received a message, but i can't unswer";
// 			$telegram->sendMessage ( [ 
// 					'chat_id' => $chatId,
// 					'text' => $response 
// 			] );
// 		}
	} catch ( Exception $e ) {
		file_put_contents ( "php://stderr", "Exception chatId:" . $chatId . " - firstname:" . $firstname . " - botName" . $botName . " - Date:" . $globalDate . " - text:" . $text . PHP_EOL );
		file_put_contents ( "php://stderr", "Exception chatId:" . $chatId . " Caught exception: " . print_r ( $e->getTraceAsString (), true ) . PHP_EOL );
	}
	
	// Stampa nel log
	file_put_contents("php://stderr", $response);
	
	function facebookSendMessage($text) {
		// https://graph.facebook.com/v2.6/me/messages?access_token=<PAGE_ACCESS_TOKEN>
		$url = "https://graph.facebook.com/v2.6/me/messages?access_token=" . $accessToken;
		$res = [
			'recipient' => [ 'id' => $chatId ],
			'message' => [ 'text' => $text ]
		];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($res));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		file_put_contents("php://stderr", "\nResult: " . $result);
		file_put_contents("php://stderr", "\nchatId: " . $chatId . ", text: " . $text);
	}