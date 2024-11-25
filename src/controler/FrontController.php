<?php

namespace controller;

use AltoRouter;
use db\Connection;
use db\URLGateWay;
use usefull\Clean;
use usefull\Validation;

class FrontController
{
    public function __construct()
    {
        global $env;
        try{
            $router = new AltoRouter();
            $router->setBasePath($env["BASEPATH"]);

            $router->map("GET|POST", "/", "Site");
            $router->map("GET", "/[a:url]", "Redirect");

            $match = $router->match();

            if ($match) {
                $params = $match["params"] ?? null;
                switch ($match["target"]) {
                    case 'Site':
                        site($params);
                        break;
                    case 'Redirect':
                        redirect($params);
                        break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}


function redirect(array $params) : void
{
    global $env;

    $gw = new URLGateWay(new Connection(
        "mysql:host=".$env['MYSQL_HOST'].";dbname=".$env['MYSQL_DATABASE'],
        $env['MYSQL_USER'],
        $env['MYSQL_PASSWORD']
    ));
    $newURL = $gw->find(["short" => $params["url"]]);
    header("Location: ".$newURL[0]["longUrl"]);
}


function site(array $params) : void
{
    global $env;

    $error = [];

    $short = $_POST["shortCut"] ?? null; // TODO: faire un aléatoire si rien de précisé
    $target = $_POST["target"] ?? null;
    if ($short != null && $target != null) {
        $error = Validation::EntryValidation($short, $target);
        if (empty($error)) {
            $gw = new URLGateWay(new Connection(
                "mysql:host=".$env['MYSQL_HOST'].";dbname=".$env['MYSQL_DATABASE'],
                $env['MYSQL_USER'],
                $env['MYSQL_PASSWORD']
            ));

            $exist = $gw->find(["short" => $short]);

            if (sizeof($exist) != 0) {
                $error [] = "The desired link is already used";
                $success = false;
            }
            else {
                $success = $gw->insert([
                    "long" => $target,
                    "short" => $short
                ]);
            }
        } else $success = false;
    }
    require "public/index.php";
}