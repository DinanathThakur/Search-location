<?php
include_once 'define.php';

// Program Logic Starts here
/**
*	Core - Initiater for http request.
*
*	Including Core file
*	Calling constructor to setup environment and start the service
*/

header('Access-Control-Allow-Origin:*');

header('Access-Control-Expose-Headers:X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept, jwt');
header('Access-Control-Expose-Headers:X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept, JWT');


include_once INFRASTRUCTURE_PATH.'Core.php';
$_core = new Core();


?>
