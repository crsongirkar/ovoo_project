<?php
// DELETED CODE
    $url ="";
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $curl_response  = curl_exec($ch);
    $curl_info      = curl_getinfo($ch);
    curl_close($ch);

    if ($curl_info["http_code"] == "200"):
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');
    $curl_response = json_decode($curl_response);
// DELETED CODE