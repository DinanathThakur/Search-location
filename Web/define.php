<?php

define('BASE_PATH', realpath('.'));
define('BASE_URL', 'http://localhost/search-location/Web/');
define('API_BASE_URL', 'http://localhost/search-location/API/');
define('GOOGLE_KEY', 'AIzaSyBLJ7aZn2_zcT5ajp0_uyq396y7xxEqWa8');

define('LAYOUT_PATH', _definingFolder('layout'));

function _definingFolder($folderName)
{
    return (($_temp = realpath($folderName)) !== false) ? $_temp . DIRECTORY_SEPARATOR : die("Invalid folder");
}
