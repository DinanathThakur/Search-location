<?php

class DB
{

    protected $connection = null;

    public function __construct()
    {

        error_reporting(0);
        header('Content-Type: application/json');

        $config = json_decode(file_get_contents( '/config.json'), true);

        $this->connection = mysqli_connect($config['host'], $config['userName'], $config['password'], $config['DB']);

        if (!$this->connection) {
            header('HTTP/1.1 500 Internal Server error');
            echo json_encode(['status' => 'error', 'msg' => "Connection failed: " . mysqli_connect_error()]);
            exit();
        }
    }

}
