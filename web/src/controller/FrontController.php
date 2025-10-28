<?php

namespace controller;

use db\Connection;
use db\URLGateWay;
use usefull\Router;
use usefull\Clean;
use usefull\Validation;

class FrontController
{
    public function __construct()
    {
        global $env;
        try{
            $router = new Router();
            $router->setBasePath($env["BASEPATH"]);

            $router->addRoute("/", "Site");
            $router->addRoute("/api", "API");
            $router->addRoute("/[string:url]", "Redirect");

            $match = $router->match();

            if ($match) {
                $params = $match["params"] ?? null;
                switch ($match["controller"]) {
                    case 'Site':
                        site($params);
                        break;
                    case 'Redirect':
                        redirect($params);
                        break;
                    case 'API':
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
    require "public/redirect.php";
}


function site(array $params) : void
{
    require "public/index.php";
}

function api(array $params) : void
{
    $BASE_URL = "http://kuturl_api:5502/api";

    $route = $_GET['route'] ?? null;
    $type = $_GET['type'] ?? null;
    if ($route === null) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => ["No route specified"],
            "uri" => ""
        ]);
        return;
    } elseif ($type === null) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => ["No type specified"],
            "uri" => ""
        ]);
        return;
    }

    $curl=curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    switch (strtoupper($type)) {
        case "GET":
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            $data = http_build_query($params);
            break;
        case "POST":
            $postParameters = json_decode($_POST["data"], true) ?? null;

            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParameters));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
    }

    $path = $BASE_URL . $route;
    curl_setopt($curl, CURLOPT_URL, $path);

    $json_response=curl_exec($curl);
    curl_close($curl);
    
    echo $json_response;
    return;
}
