<?php

defined('INFRASTRUCTURE_PATH') or exit('No direct script access allowed');

class Request
{

    protected $inputObject = null;

    public function __construct()
    {
        $this->inputObject = $_REQUEST;
    }

    public function get($key)
    {
        return $this->inputObject[$key];
    }

    function isset($key, $empty = false) {
        return $empty ? isset($this->inputObject[$key]) && $this->inputObject[$key] != '' : isset($this->inputObject[$key]);
    }

    public function issetGet($key, $empty = false, $defaultValue = null)
    {
        return $this->isset($key, $empty) ? $this->get($key) : $defaultValue;
    }
    public function getAll()
    {
        return $this->inputObject;
    }

}
