<?php
/*----------DEFINE-----------*/
define("POLLING_DELAY", 15);
/*----------VARIABLES--------*/
$OFFSET = -1;
/*---------MARKUP------------*//*
## reply keyboard ##
    keyboard = [
      ["str1","str2"],
      ["str3","str4"]
    ] ->  matrix of strings
    resize_keyboard = true -> (optional) resize the keyboard to optimal fit
    one_time_keyboard = true -> (optional) show keyboard only one time

## inline keyboard ##
  inline_keyboard = [
    [ [k1] , [k2] ],
    [ [k3] , [k4] ]
  ] matrix of keyboardbutton objects

        keyboardbutton objects:
          text = "str" ->  label text string
          url = "url" -> (optional) url to be opened
          callback_data -> (optional) data to be sent in a callback_query

## force reply ##
  force_reply = true -> show reply interface to the user
/*---------------------------*/

/*------------------------------------------------------------------------*/

interface iTELEGRAM {
  public function __construct($tok);
  public function send_photo($photo_path = "", $chat_ID = 0);
  public function send_text($text = "", $chat_ID = 0, $markup = NULL);
  public function isavailable_text();
  public function read_text();
  public function wait_for_text();
}

/* telegram class */
class TELEGRAM implements iTELEGRAM
{
  const max_lenght = 4095;
  private $OFFSET = -1;
  private $API_URL = "https://api.telegram.org/bot";
  private $TOKEN = "";
  public $USER_ID = "";
  public $USER_CHAT_ID = "";
  public $USER_FIRST_NAME = "";
  public $USER_USERNAME = "";
  public $TEXT = "";
  public $REPLY_TO_MESSAGE = "";

  public function __construct($tok)
  {
    $this->TOKEN = $tok;
  }

  public function send_photo($photo_path = "", $chat_ID = 0)
  {
    $url = $this->API_URL.$this->TOKEN."/sendphoto";
    $data = array("chat_id" => $chat_ID, "photo" => $photo_path);
    curl_post($url,$data);
  }

  public function send_text($text = "", $chat_ID = 0, $markup = NULL)
  {
    if(strlen($text) >= $this::max_lenght){
      return FALSE;
    }

    $url = $this->API_URL.$this->TOKEN."/sendmessage";
    if($markup == NULL)
      $data = array("chat_id" => "$chat_ID", "text" => $text, "parse_mode" => "HTML");
    else {
      $json_markup = json_encode($markup);
      $data = array("chat_id" => "$chat_ID", "text" => $text, "reply_markup" => $json_markup, "parse_mode" => "HTML");
    }
    curl_post($url,$data);
    return TRUE;
  }

  public function isavailable_text(){
    if( $this->TEXT != "" ) return TRUE;
    else return FALSE;
  }

  /*Reads an incoming text message, and updates the offset*/
  /*return FALSE in case of error*/
  public function read_text()
  {
    $this->unset();
    /*wait for incoming messages*/
    $json_message = file_get_contents($this->API_URL.$this->TOKEN."/getupdates?timeout=".POLLING_DELAY."&offset=$this->OFFSET");
    /*check for connection errors*/
    if($json_message == FALSE) return FALSE;

    /*decode the jeson output to string*/
    $message = json_decode($json_message, TRUE);

    /*if there is a message save it*/
    if( isset($message["result"][0]["message"]["text"]) ){
      /*assign readed strings*/
      $this->assign_text($message);
      if( isset($message["result"][0]["message"]["reply_to_message"]) )
        $this->REPLY_TO_MESSAGE = $message["result"][0]["message"]["reply_to_message"]["text"];

    }elseif ( isset($message["result"][0]["callback_query"]["data"])) {
      $this->assign_text_callback($message);
    }

    return TRUE;
  }

  public function wait_for_text(){
    while(1){
      if( $this->read_text() == FALSE ){
        echo "\n[  ERROR  ] error while connecting to the server, please wait 15s\n";
        sleep(15);
      }
      elseif( $this->isavailable_text() ) return;
    }
  }

  private function unset()
  {
    $this->USER_ID = "";
    $this->USER_CHAT_ID = "";
    $this->USER_FIRST_NAME = "";
    $this->USER_USERNAME = "";
    $this->TEXT = "";
    $this->REPLY_TO_MESSAGE = "";
  }

  private function answer_callback_query($query_id = 0)
  {
    $url = $this->API_URL.$this->TOKEN."/answercallbackquery";
    $data = array("callback_query_id" => $query_id);
    curl_post($url,$data);
  }

  private function assign_text_callback($message){
    $this->OFFSET = $message["result"][0]["update_id"] + 1;
    $callback_query_id = $message["result"][0]["callback_query"]["id"];
    $this->TEXT = $message["result"][0]["callback_query"]["data"];
    $this->USER_CHAT_ID = $message["result"][0]["callback_query"]["message"]["chat"]["id"];
    $this->answer_callback_query($callback_query_id);
  }

  /*save the text message*/
  private function assign_text($message)
  {
    /* read and update the offset */
    $this->OFFSET = $message["result"][0]["update_id"] + 1;

    /* read the user ID */
    $this->USER_ID = $message["result"][0]["message"]["from"]["id"];

    /* read the user chat id*/
    $this->USER_CHAT_ID = $message["result"][0]["message"]["chat"]["id"];

    /* read the first name */
    $this->USER_FIRST_NAME = $message["result"][0]["message"]["from"]["first_name"];

    /* check if username it's present */
    if( isset($message["result"][0]["message"]["from"]["username"]) )
      /*if yes save it*/
      $this->USER_USERNAME = $message["result"][0]["message"]["from"]["username"];

    /*save text message*/
    $this->TEXT = $message["result"][0]["message"]["text"];

  }
}


/*------------------------------------------------------------------------*/



?>
