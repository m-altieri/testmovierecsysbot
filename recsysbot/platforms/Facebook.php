<?php 

require_once "recsysbot/platforms/Platform.php";
require "recsysbot/facebook/sendMessage.php";
require "recsysbot/facebook/setBotProfile.php";
require "recsysbot/facebook/setGetStarted.php";
require "recsysbot/facebook/setGreeting.php";
require "recsysbot/facebook/setPersistentMenu.php";
$config = require_once '/app/recsysbot/config/movierecsysbot-config.php';


class Facebook implements Platform {
	
	public function sendMessage($array) {
		sendMessage($array['chat_id'], $array['text']);
		/*
		 * Aggiungere l'invio dei quick reply
		 */
	}
	
	public function sendPhoto($array) {
		
	}
	
	public function sendChatAction($array) {
		
	}
	
	private function replyKeyboardMarkup($keyboard) {
		
		$quick_replies = array();
		
		foreach ($keyboard as $item) {
			$quick_replies['content_type'] = 'text';
			$quick_replies['title'] = $item;
			$quick_replies['payload'] = $item;
		}
		
		return $quick_replies;
	
	}
	
	public function getWebhookUpdates() {
		return file_get_contents("php://input");
	}
	
	public function getMessageInfo($json) {
		
	}
	
	public function commandsHandler($boolean) {
		
	}
	
	public function addCommand($commandClass) {
		
	}
	
}

?>