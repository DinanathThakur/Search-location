<?php
include_once  'validateJWT.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Location</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/toastr/toastr.min.css" rel="stylesheet"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var API_BASE_URL = '<?php echo API_BASE_URL;?>';
        var BASE_URL = '<?php echo BASE_URL;?>';
        var ASSET_URL = '<?php echo BASE_URL;?>assets/';
    </script>
    <script src="<?php echo BASE_URL; ?>assets/main.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/blockUI.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:;">Hi - <?php echo $session['data']['name'] ?></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="javascript:;" id="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>
