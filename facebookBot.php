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
		public function returnBot(){
			return file_get_contents("php://input");
		}
	}
$a = new botFacebook("test1234","");
file_put_contents("test.txt",$a->returnBot());