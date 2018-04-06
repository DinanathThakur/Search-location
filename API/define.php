<?php

define('BASE_URL', 'http://localhost/');
define('INFRASTRUCTURE_PATH', _definingFolder('Infrastructure'));
define('MICROSERVICE_PATH', _definingFolder('Microservice'));
define('COMMON_PATH', _definingFolder('Common'));

function _definingFolder($folderName)
{
    return (($_temp = realpath($folderName)) !== false) ? $_temp . DIRECTORY_SEPARATOR : die("Invalid folder");
}
