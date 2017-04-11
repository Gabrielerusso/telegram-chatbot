<?php

function weather_now($city = "Torino"){
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


/*------------------------------------------------------------------------*/


function weather_tomorrow($city = "Torino"){

}


/*------------------------------------------------------------------------*/


function insulta($name = ""){


}

?>
