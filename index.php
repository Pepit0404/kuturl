<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require './vendor/autoload.php';

$env = parse_ini_file('.env');

$con = new \controller\FrontController();