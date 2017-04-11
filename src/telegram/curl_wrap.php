<?php

/*Sends data via POST method using curl*/
function curl_post($url = NULL, $data = NULL){
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    curl_exec($curl);
    curl_close($curl);
}

?>
