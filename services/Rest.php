<?php
	class REST {

		public $_allow = array();
		public $_content_type = "application/json";
		public $_request = array();

		private $_method = "";
		private $_code = 200;

		public function __construct(){
			$this->inputs();
		}

		public function get_referer(){
			return $_SERVER['HTTP_REFERER'];
		}

		public function response($data,$status){
			$this->_code = ($status)? $status : 200;
			$this->set_headers();
			echo $data;
			exit;
		}

		// Reference:- http://en.wikipedia.org/wiki/List
		private function get_status_message(){
			$status = array(
						200 => 'OK',
						201 => 'Created',
						204 => 'No Content',
						404 => 'Not Found',
						406 => 'Not Acceptable');
			return ($status[$this->_code]) ? $status[$this->_code] : $status[500];
		}

		public function get_request_method(){
			return $_SERVER['REQUEST_METHOD'];
		}

		private function inputs(){
			switch($this->get_request_method()){
				case "POST":
					$this->_request = $this->cleanInputs($_POST);
					break;
				case "GET":
					$this->_request = $this->cleanInputs($_GET);
					break;
				default:
					$this->response('',406);
					break;
			}
		}

		/*
			cleanInputs:-
				params: $data
				returns: $clean_data
				need to add xss_clean for post requests
		*/
		private function cleanInputs($data){
			$clean_input = array();
			if(is_array($data)){
				foreach($data as $k => $v){
					$clean_input[$k] = $this->cleanInputs($v);
				}
			}else{
				//get current config of magic_quotes_gpc
				if(get_magic_quotes_gpc()){
					$data = trim(stripslashes($data));
				}
				$data = strip_tags($data);
				$clean_input = trim($data);
			}
			return $clean_input;
		}

		private function set_headers(){
			header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
			header("Content-Type:".$this->_content_type);
		}
	}
?>
