<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require 'src/autoload.php';
use controller\FrontController;

$env = parse_ini_file('.env');

session_start();

$con = new FrontController();
