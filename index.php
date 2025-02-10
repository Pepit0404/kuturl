<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require './vendor/autoload.php';

$env = parse_ini_file('.env');

session_start();

$con = new \controller\FrontController();
