<?php

  require_once('lib/twitteroauth/autoload.php');
  require_once('constants/twitter_config.php');
  use Abraham\TwitterOAuth\TwitterOAuth;

  class TwitterController extends API {

    private $tweet_data = [];
    // setting default count to 25
    private $count = 25;

    public function __construct(){
      parent::__construct();				// Init parent contructor
      $this->connect_api();
    }

    // exposed public method
    public function get(){
      $hashtag = str_replace("#", "", $this->_request["hashtag"]);
      $max_id = $this->_request['max_id'];
      $query = $this->create_query_string($hashtag, $max_id);
      $results = $this->twitter_fetcher->get("search/tweets", $query);
      $tweets = $results->statuses;
      $this->tweet_data = $this->extract_retweeted_tweets($tweets);
      return $this->tweet_data;
      if(sizeof($this->tweet_data["tweets"])  == 0){
        return null;
      }else{
        return $this->tweet_data;
      }
    }

    //generate query for twitter search api
    private function create_query_string($hashtag, $max_id){
      $query = array(
                "q" => "#".$hashtag,
                "count" => $count
              );
      if($max_id !== null){
        $query["max_id"] = $max_id;
      }
      return $query;
    }

    // extract tweets retweeted atleast once
    private function extract_retweeted_tweets($tweets){
      $size = sizeof($tweets);
      $result = array(
                  "tweets" => [],
                  "max_id" => ''
                );
      // return null for empty data
      if($size == 0){
        $result = null;
      }else{
        $result["max_id"] = $tweets[sizeof($tweets) - 1]->id;
        for($i = 0; $i < $size; $i++){
          $obj = $tweets[$i];
          if($obj->retweet_count !== 0){
            $result['tweets'][$obj->id] = $obj;
          }
        }
      }
      return $result;
    }

    /*
      Initialize twitter lib
    */
    private function connect_api(){
      $this->twitter_fetcher = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,
                                            ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    }


  }

?>
