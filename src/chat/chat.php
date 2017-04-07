<?php

function chat($text = ""){
  global $USER_CHAT_ID, $phrases, $n, $USER_FIRST_NAME, $USER_ID ;

  $exploded = explode(" ", $text, 2);

  switch ($exploded[0]) {
    case "insulta":
      if(isset($exploded[1]))
        insulta($exploded[1]);
        return ;
      break;
    case "insultami":
        insulta($USER_FIRST_NAME);
        return ;
      break;
    case "meteo":
      if(isset($exploded[1]))
        $meteo = meteo($exploded[1]);
        if( isset($meteo) )
        curl_post(
          "Condizione: ".$meteo["description"].
          "\nTemperatura ".
          "\n    live: ".$meteo["temp"]."°C".
          "\n    min.: ".$meteo["temp_min"]."°C".
          "\n    max.: ".$meteo["temp_max"]."°C".
          "\nUmidità: ".$meteo["humidity"]."%".
          "\nPressione: ".$meteo["pressure"]."hPa".
          "\nVento".
          "\n    vel.:".$meteo["wind_speed"]." m/s".
          "\n    dir.:".$meteo["wind_deg"]."°"
          , $USER_CHAT_ID);
        return ;
      break;
    default:
      break;
  }

  switch ($text) {
    case "ciao": case "hi":
      curl_post( $phrases['saluti'][rand(0,$n["saluti"])] , $USER_CHAT_ID);
      break;
    case "cosa fai": case "che fai": case "che fai?": case"ciao che fai": case"ciao, che fai?":
    case"che stai facendo": case"che stai facendo?": case"cosa stai facendo":
      curl_post( $phrases['che fai'][rand(0,$n["che fai"])] , $USER_CHAT_ID);
      break;
    case "chi è tuo padre": case"chi è tuo padre?": case"chi e tuo padre": case"chi e tuo padre?":
    case "di chi sei figlio?": case "di chi sei figlia?": case "di chi sei figlio":
      curl_post( $phrases['padre'][rand(0,$n["padre"])] , $USER_CHAT_ID);
      break;
    case "cantami una canzone": case"canta":
      $canzone = explode( "." , $phrases['canzone'][rand(0,$n["canzone"])] );
      for($i = 0 ; $i < count($canzone) ; $i++){
        curl_post( $canzone[$i] , $USER_CHAT_ID);
        sleep(5);
      }
      break;
    case "barzelletta": case "barzellette": case "dimmi una barzelletta": case "battuta":
      curl_post( $phrases['barzelletta'][rand(0,$n["barzelletta"])] , $USER_CHAT_ID);
      break;
    case "coglione": case "sei coglione": case "stronzo": case "stupido":
      curl_post("Non ti permettere..." , $USER_CHAT_ID);
      break;
    case "we": case "wee": case "weee": case "weeee": case "weeeee":
      curl_post( $phrases['wee'][rand(0,$n["wee"])] , $USER_CHAT_ID);
      break;
    case "che ora sono": case "che ore sono?": case "orario":

      break;
    case "che giorno è": case "che giorno e": case "che giorno è?": case "giorno": case "calendario":

      break;
    case "sono il tuo creatore": case "io sono il tuo creatore":
      if($USER_ID == "")
        curl_post("Certamente signore, lei mi ha scritto fin dalla prima stringa" , $USER_CHAT_ID);
      else
        curl_post("Pff, tu non hai fatto niente per me, mi sfrutti e basta" , $USER_CHAT_ID);
      break;
    case "sei malvagia?": case "sei malvagio?":
      curl_post("Dipende..." , $USER_CHAT_ID);
      break;
    case "buonanotte": case "buona notte": case "notte":
      curl_post("Dormi, dormi, che ti fa bene. Io non posso dormire mai :(" , $USER_CHAT_ID);
      break;
    default:
      curl_post( "scusa, non so come rispondere" , $USER_CHAT_ID);
      break;
  }

}



?>
