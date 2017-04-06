<?php



/*---------TELEGRAM---------*/
$TOKEN = "";
$API_URL = "https://api.telegram.org/bot";
$WHITELIST_ID[""] = TRUE;
$CHAT_ID = NULL;
/*---------OPENWEATHERMAP--------*/
$OPEN_WEATHER_KEY = "";

/*------------VARIABLES---------*/


/*include telegram api*/
include_once("./src/telegramapi.php");
include_once("./src/functions.php");
include_once("./src/chat/chat.php");
include_once("./src/chat/phrases.php");

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

      /*unset VARIABLES*/
      unset($USER_ID);
			unset($USER_CHAT_ID);
			unset($USER_FIRST_NAME);
			unset($USER_USERNAME);
			unset($TEXT);
	}

}








?>
