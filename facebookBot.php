<?php

	class botFacebook{
		const BASE_URL_APIFB = 'https://graph.facebook.com/v2.6/';
		private $configMessage =  array(
		'sito' => "Al seguente indirizzo: https://www.relaxtraveltours.com/ , potrai trovare il nostro sito web.");
		private $valToken;
		private $pageToken;
		public function __construct($val,$page){
			$this->configMessage['prova'] = function(){
			$url = self::BASE_URL_APIFB . "me/messages?access_token=%s";
			
			$url = sprintf($url, $this->pageToken);
			$parameters = [];
			$parameters["recipient"]["id"] = $this->recipientId;
			$parameters["message"]["attachment"]["type"] = "template";
			$parameters["message"]["attachment"]["payload"]["template_type"] = "button";
			$parameters["message"]["attachment"]["payload"]["text"] = "Prova";
			$parameters["message"]["attachment"]["payload"]["buttons"] = [];
			$parameters["message"]["attachment"]["payload"]["buttons"][] = ["type" => "web_url","url" => "https://www.relaxtraveltours.com/","title" => "Visita il sito"];
			self::executePost($url,$parameters);
		};
			$this->valToken = $val;
			$this->pageToken = $page;
			self::setupWebhook();
		}
		private function setupWebhook()
			{
				if (isset($_REQUEST['hub_challenge']) && isset($_REQUEST['hub_verify_token']) && $this->valToken == $_REQUEST['hub_verify_token']) {
					echo $_REQUEST['hub_challenge'];
					exit;
				}
			}
		public function returnMessage(){
			$return = null;
			try{
				
				$return = json_decode(file_get_contents("php://input"), false, 512, JSON_BIGINT_AS_STRING);
			}
			catch(PDOException $e){
				$return = null;
			}
			return $return;
		}
		
		public function replyMessage($mex){
			$return = null;
			if($mex && $mex = strtolower($mex) && @$return = $this->configMessage[$mex] && $return && ! is_callable($return)){
				
			}
			else if($return && is_callable($return)){
				$return();
			}
			return $return;
		}
		private static function executePost($url, $parameters, $json = false){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			if ($json) {
				$data = json_encode($parameters);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
			} else {
				curl_setopt($ch, CURLOPT_POST, count($parameters));
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
		
		public function sendTextMessage($recipientId, $text){
			$url = self::BASE_URL_APIFB . "me/messages?access_token=%s";
			
			$url = sprintf($url, $this->pageToken);
			$parameters = [];
			$parameters["recipient"]["id"] = $recipientId;
			$parameters["message"]["text"] = $text;
		
			$response = self::executePost($url, $parameters, true);
			if ($response) {
				$responseObject = json_decode($response);
				return is_object($responseObject) && isset($responseObject->recipient_id) && isset($responseObject->message_id);
			}
			return false;
		}
		
		
	}
$bot = new botFacebook("test1234","EAAYG4kbMNqcBAOqEVyquknrTpudyahcvs2onRQDegDR0VHaSGf04qktv7M1ZAglPlI76SpVCmxnc7mnuWQO26tYZB16HFJZBaxdxASnYSwUPlWcIZCsYVdAvywqaBD0gFBh1zJYiks7P9M6vZA9kxPpPcf2G4t7ywOXPMOqYPZCwZDZD");
$message = $bot->returnMessage();
if($message){
	file_put_contents("test.txt","

".json_encode($message),FILE_APPEND);
	try{
	if($message->object == "page"){
		$entry = $message->entry;
		foreach($entry as $en){
			$idPage = $en->id;
			$mexs = $en->messaging;
			foreach($mexs as $mex){
				$sender = $mex->sender->id;
				if($sender !== $idPage){
					$messages = $mex->message->text;
					$this->recipientId = $sender;
					$sendMessage = $bot->replyMessage($messages);
					if($sendMessage){
						$bot->sendTextMessage($sender,$sendMessage);
					}
				}
			}
		}
		
	}
	}catch(Exception $e){
		file_put_contents("error.log",$e);
	}
}