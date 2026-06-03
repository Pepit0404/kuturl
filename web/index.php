<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

require "src/autoload.php";
use controller\FrontController;

if (file_exists(".env")) {
    $env = parse_ini_file(".env");
} else {
    $env = [
        "BASEPATH" => getenv("BASEPATH"),
    ];
}

session_start();

$con = new FrontController();
