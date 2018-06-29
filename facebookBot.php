<?php
	class botFacebook{
		const BASE_URL_APIFB = '';
		private $valToken,$pageToken;
		public function __construct($val,$page){
			$this->valToken = $val;
			$this->pageToken = $page;
		}
		public function returnBot(){
			return file_get_contents("php://input");
		}
	}
file_put_contents("test.txt",$botFacebook->returnBot());