<?php
/*Selects the right function from given command*/
function select_function(){

	global $USER_CHAT_ID, $TEXT;
  $text = explode ( " " , $TEXT, 2);
	switch($text[0]){
    case "/chat":
      if( isset($text[1]) )
        chat($text[1]);
      break;
    case "/insulta":
      if( isset($text[1]))
        insulta($text[1]);
      break;
		case "/start":
			curl_post("Bot avviato" , $USER_CHAT_ID);
			echo "Bot started\n";
			break;
		case "/stato":
			curl_post("Tutto bene, grazie" , $USER_CHAT_ID);
			break;
		default:
			curl_post("Comando non riconosciuto" , $USER_CHAT_ID);
	}
}




function meteo($city = "Torino"){
  global $OPEN_WEATHER_KEY;
  $weather= json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$city&lang=it&units=metric&appid=$OPEN_WEATHER_KEY"), TRUE );
  if( isset($weather["weather"]) ){
    $vect["description"] = $weather["weather"][0]["description"];
    $vect["icon"] = $weather["weather"][0]["icon"];
    $vect["temp"] = $weather["main"]["temp"];
    $vect["temp_min"] = $weather["main"]["temp_min"];
    $vect["temp_max"] = $weather["main"]["temp_max"];
    $vect["pressure"] = $weather["main"]["pressure"];
    $vect["humidity"] = $weather["main"]["humidity"];
    $vect["wind_speed"] = $weather["wind"]["speed"];
    $vect["wind_deg"] = $weather["wind"]["deg"];
    $vect["clouds"] = $weather["clouds"]["all"];
    if(isset($weather["rain"]))
      $vect["rain"] = $weather["rain"]["3h"];
    if(isset($weather["snow"]))
      $vect["snow"] = $weather["snow"]["3h"];
    return $vect;
  }
  return NULL;
}


function insulta($nome = ""){
  global $phrases, $n, $USER_CHAT_ID;
  if( $nome != "gabriele" && $nome != "Gabriele" && $nome != "GABRIELE" && $nome != "gabry" && $nome != "@wedontgiveabot" && $nome != "@Whaterb" )
    curl_post( $nome." ".$phrases["insulti"][rand(0,$n["insulti"])] ,  $USER_CHAT_ID);
  else
    curl_post( "A ti pari chi sugnu bestia?" , $USER_CHAT_ID);
}

?>
