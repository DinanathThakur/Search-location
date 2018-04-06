<?php
include_once 'define.php';

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

header('Access-Control-Expose-Headers:X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept, jwt');
header('Access-Control-Expose-Headers:X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept, JWT');

include_once INFRASTRUCTURE_PATH . 'Core.php';
$_core = new Core();
