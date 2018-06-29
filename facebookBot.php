<?php
error_reporting(E_ERROR);
	class botFacebook{
		const BASE_URL_APIFB = '';
		private $valToken;
		private $pageToken;
		public function __construct($val,$page){
			$this->valToken = $val;
			$this->pageToken = $page;
		}
		public function returnBot(){
			return file_get_contents("php://input");
		}
	}
$a = new botFacebook();
file_put_contents("test.txt",$a->returnBot());