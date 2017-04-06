<?php
/*----------DEFINE-----------*/
define("POLLING_DELAY", 15);
/*----------VARIABLES--------*/
$OFFSET = -1;
$error_counter = 0;


/*Sends a text message via POST method using curl*/
function curl_post($text = "", $chat_ID = 0){
    global $API_URL, $TOKEN;

    $data = array("chat_id" => "$chat_ID", "text" => "$text", "parse_mode" => "HTML");
    $curl = curl_init($API_URL.$TOKEN."/sendmessage");

    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    curl_exec($curl);
    curl_close($curl);
}




/*Reads an incoming text message, and updates the offset*/
function read_incoming_text(){

  /* set the global variables */
  global $OFFSET, $USER_ID, $USER_CHAT_ID, $USER_FIRST_NAME, $USER_USERNAME;
  global $TEXT, $message;

  /* read and update the offset */
  $OFFSET = $message["result"][0]["update_id"] + 1;

  /* read the user ID */
  $USER_ID = $message["result"][0]["message"]["from"]["id"];

  /* read the user chat id*/
  $USER_CHAT_ID = $message["result"][0]["message"]["chat"]["id"];

  /* read the first name */
  $USER_FIRST_NAME = $message["result"][0]["message"]["from"]["first_name"];

  /* check if username it's present */
  if( isset($message["result"][0]["message"]["from"]["username"]) )
    /*if yes save it*/
    $USER_USERNAME = $message["result"][0]["message"]["from"]["username"];

  /* check a text message it's present */
  if( isset($message["result"][0]["message"]["text"]) )
    /*if yes save it*/
    $TEXT = $message["result"][0]["message"]["text"];
}


?>
