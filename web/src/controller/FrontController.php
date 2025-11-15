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
            $router->addRoute("/api/[*:]", "API");
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
    $BASE_URL = "http://kuturl_api:4481";

    $uri = $_SERVER['REQUEST_URI'];

    $request = $BASE_URL . $uri;

    $curl=curl_init();
    curl_setopt($curl, CURLOPT_URL, $request);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);

    $postParameters = file_get_contents('php://input') ?? null;
    if (!empty($postParameters)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postParameters);
    }

    // ** --- HEADERS --- **
    $request_headers = getallheaders();
    $curl_headers = [];
    foreach ($request_headers as $key => $value) {
        if (!in_array(strtolower($key), ['host', 'connection', 'content-length', 'accept-encoding'])) {
            $curl_headers[] = "$key: $value";
        }
    }

    if (!empty($curl_headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $curl_headers);
    }

    $response=curl_exec($curl);
    
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $response_body = substr($response, $header_size);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    
    http_response_code($http_code);

    curl_close($curl);
    
    echo $response_body;
    return;
}
