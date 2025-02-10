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
            $router->map("GET|POST", "/api/[a:command]", "Api");
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
                    case 'Api':
                        api($params);
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

    $gw = new URLGateWay();
    $newURL = $gw->find(["short" => $params["url"]]);
    if ($newURL) {
        header("Location: ".$newURL[0]["longUrl"]);
    } else {
        http_response_code(404);
        // include(); // TODO: mettre une page 404
        die();
    }
}


function site(array $params) : void
{
    require "public/index.php";
}

function api(array $params) : void
{
    $error = [];

    $short = $_POST["shorter"] ?? null; // TODO: faire un aléatoire si rien de précisé
    $target = $_POST["original"] ?? null;

    if ($short == null || $target == null) // TODO: changer ça car duplication de code
    {
        $response = array(
            "success" => false,
            "message" => array('One of the parameter is empty'),
            "uri" => ""
        );

        $jsonResponse = json_encode($response);
        header('Content-Type: application/json');
        echo $jsonResponse;
        return;
    }

    $error = Validation::EntryValidation($short, $target);

    if (!empty($error)) {
        $success = false;
    } else {

        $gw = new URLGateWay();

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
            $error [] = $success ? "Link created" : "$success = false";
        }
    }

    $response = array(
        "success" => $success,
        "message" => $error,
        "uri" => $short
    );

    $jsonResponse = json_encode($response);
    header('Content-Type: application/json');
    echo $jsonResponse;
}