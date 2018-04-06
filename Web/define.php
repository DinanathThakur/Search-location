<?php

define('MICROSERVICE_PATH', _definingFolder('Microservice'));
define('COMMON_PATH', _definingFolder('Common'));

function _definingFolder($folderName)
{
    return (($_temp = realpath($folderName)) !== false)
    ? $_temp . DIRECTORY_SEPARATOR
    : strtr(rtrim($folderName, '/\\'), '/\\', DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}
