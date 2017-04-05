<?php

/*----------DEFINE-----------*/
define("POLLING_DELAY", 15);

/*---------TELEGRAM---------*/
$TOKEN = "";
$API_URL = "https://api.telegram.org/bot";
$WHITELIST_ID[""] = TRUE;
$CHAT_ID = NULL;

/*------------VARIABLES---------*/
$current_t0 = 0;
$current_t1 = 0;
$OFFSET = -1;
$error_counter = 0;


/*main loop*/
while(1){

  /*wait for incoming messages*/
	$json_message = file_get_contents($API_URL.$TOKEN."/getupdates?timeout=".POLLING_DELAY."&offset=$OFFSET");

	/*check if there are errors*/
	if($json_message == FALSE){
		echo "\n[  ERRORE   ] Errore di connessione con il server\n";
		$error_counter++;
		if( $error_counter > 5 )
			sleep(120);
		else
			sleep(6);

	}
	else
		$error_counter = 0;
	/*decode the jeson output to string*/
	$message = json_decode($json_message, TRUE);

	/*If the message isn't empty continue, else wait again*/
	if( isset($message["result"][0]) ){
			/*read and decode the incoming text message*/
			read_incoming_text();

      /*select function*/
			select_function();
      unset($USER_ID);
			unset($USER_CHAT_ID);
			unset($USER_FIRST_NAME);
			unset($USER_USERNAME);
			unset($TEXT);
		}



}


/*Selects the right function from given command*/
function select_function(){

	global $USER_CHAT_ID, $external_IP, $TEXT, $current_t0, $maxtemp, $mintemp;

	switch($TEXT){
			case "/start":
			curl_post("Bot avviato" , $USER_CHAT_ID);
			echo "Bot started\n";
			break;
		case "/stato":
			curl_post("Tutto bene, grazie" , $USER_CHAT_ID);
			echo "Status\n";
			break;
		default:
			curl_post("Comando non riconosciuto" , $USER_CHAT_ID);
			echo "Unknow\n";
	}
}


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
