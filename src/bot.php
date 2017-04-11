<?php
/*---------TELEGRAM---------*/
$TOKEN = "";
$CHAT_ID = NULL;
/*---------OPENWEATHERMAP--------*/
$OPEN_WEATHER_KEY = "";

/*------------VARIABLES---------*/
$error_counter = 0;

/*include */
include_once("./telegram/telegramapi.php");
include_once("./telegram/curl_wrap.php");
include_once("./commands/commands.php");
include_once("./chat/chat.php");
include_once("./chat/phrases.php");

$telegram = new TELEGRAM($TOKEN);

/*-------------nosense - test purpose only------*/
$handle = fopen("./chat/frasi.txt", "r");
while( fscanf($handle, "#%s", $type) ){
  if($type == "") break;
  while( fscanf($handle, "%[^\n]", $frase) ){
    if(substr($frase, -1) != "#"

    $phr[$type][] = $frase;
  }
}

var_dump($phr);
/*----------------------------------------------*/
       
       
/*main loop*/
while(1){

  if( $telegram->read_text() == FALSE ){
    echo "\n[  ERROR  ] error while connecting to the server, please wait 15s\n";
    sleep(15);
  }

  if( $telegram->isavailable_text() ){
    $USER_CHAT_ID = $telegram->USER_CHAT_ID;
    $TEXT = $telegram->TEXT;
    $USER_FIRST_NAME = $telegram->USER_FIRST_NAME;
    select_function();
  }

}

/*Selects the right function from given command*/
function select_function(){
  global $telegram;

  $MESSAGE["USER_CHAT_ID"] = $telegram->USER_CHAT_ID;
  $MESSAGE["TEXT"] = $telegram->TEXT;
  $MESSAGE["USER_FIRST_NAME"] = $telegram->USER_FIRST_NAME;

  if ($telegram->REPLY_TO_MESSAGE != "") {
    switch ($telegram->REPLY_TO_MESSAGE) {
      case "Chi devo insultare?":
        insulta($MESSAGE("TEXT"));
        break;
      case "Dimmi pure...":
        chat($MESSAGE("TEXT"));
        break;
    }
    return;
  }

  $text = explode ( " " , $MESSAGE["TEXT"], 2);

	switch($text[0]){
    case "/test": case "/test@wedontgiveabot":
      break;
    case "/chat": case "/chat@wedontgiveabot":
      if( isset($text[1]) )
        chat($MESSAGE);
      break;
    case "/insulta": case "/insulta@wedontgiveabot":
      if( isset($text[1]))
        insulta($text[1]);
        else
          {
            $markup["force_reply"] = true;
            $telegram->send_text("Chi devo insultare?", $MESSAGE["USER_CHAT_ID"], $markup);
            unset($markup);
          }
      break;
		case "/start": case "/start@wedontgiveabot":
			$telegram->send_text("Bot started", $MESSAGE["USER_CHAT_ID"]);
			break;
		case "/stato": case "/stato@wedontgiveabot":
			$telegram->send_text("Tutto bene, grazie", $MESSAGE["USER_CHAT_ID"]);
			break;
		default:
			$telegram->send_text("Comando non riconosciuto", $MESSAGE["USER_CHAT_ID"]);
	}
}






?>
