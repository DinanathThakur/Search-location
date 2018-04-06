<?php
$jwt = isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : null;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_BASE_URL . '?apiName=ValidateJWT');
curl_setopt($ch, CURLOPT_POST, 1); //0 for a get request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["jwt:$jwt"]);
$session = json_decode(curl_exec($ch), true);

if (isset($session['result']) && $session['result'] == 'failure') {
    header('Location: ' . BASE_URL);
}
