<?php
error_reporting(E_ERROR);
	class botFacebook{
		const BASE_URL_APIFB = '';
		private $valToken;
		private $pageToken;
		public function __construct($val,$page){
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
	}
$bot = new botFacebook("test1234","");
$message = $bot->returnMessage();
if($message){
	file_put_contents("test.txt",json_encode($message));
}