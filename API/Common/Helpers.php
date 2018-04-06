<?php
function getSessionDetails()
{

}
function setHeader()
{

}
function setHTTPResponseCode($code)
{
    http_response_code($code);
}

function throughError($message, $httpResponseCode = null)
{
    if ($httpResponseCode) {
        setHTTPResponseCode($httpResponseCode);
    }
    exit(json_encode(array('result' => 'error', 'msg' => $message)));
}
