<?php
 	require_once("Rest.php");

	class API extends REST {

		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}

    private $request_url = [];

		/*
		 * Dynmically call the method based on the query string
	  */
		public function processApi(){
      $x = $_REQUEST['x'];
      $this->request_url = explode("/", $x);
      $func = strtolower(trim(str_replace("/","",$this->request_url[0])));
      if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class "Page not found".
		}

    private function tweets(){
      if($this->get_request_method() != "GET"){
  				$this->response('',406);
			}else{
        require('twitter_controller.php');
        $twitter_controller = new TwitterController();
        $func = $this->request_url[1];
          if((int)method_exists($twitter_controller, $func) > 0){
  				$this->data = $twitter_controller->$func();
          $this->response($this->json($this->data), 200);
        }else{
          $this->response('',404); // If the method not exist with in this class "Page not found".
        }
      }
    }

    /*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

	// Initiate Library

	$api = new API;
	$api->processApi();
?>
