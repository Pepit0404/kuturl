<?php

namespace usefull;

class Router {
    private string $basePath;
    private array $routes;
    private array $paramTypes = [
        'integer'  => '[0-9]+',
        'string'  => '[0-9A-Za-z]+',
        'strictString' => '[a-zA-Z]+',
        '*' => '.+'
    ];

    public function __construct(string $basePath="")
    {
        $this->setBasePath($basePath);
    }

    public function setBasePath(string $basePath): void
    {
        $tmp = explode("/", $basePath);
        $this->basePath = join("\/", $tmp);
    }

    public function addType(string $name, string $regex): void
    {
        $this->paramTypes[$name] = $regex;
    }

    public function addRoute(string $route, string $controller): void
    {
        $newRoute = [];
        $params = [];
        foreach (explode("/", $route) as $part) {
            if (str_contains($part, "[")) {
                $part = trim($part, "[] ");
                $split = explode(":", $part);
                $params[] = $split[1]; // 1 = parameter name's
                $newRoute[] = $this->paramTypes[$split[0]]; // 0 = parameter type
            } else {
                $newRoute[] = $part;
            }
        }

        $this->routes[join("\/", $newRoute)] = ["params" => $params, "controller" => $controller];
    }

    public function match(?string $request = null): array | bool
    {
        if ($request === null) {
            $request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }

        $request = explode("?", $request)[0];

        $params = [];
        $result = "";
        foreach ($this->routes as $route => $data) {
            $longRoute = $this->basePath . $route;
            if (preg_match("/^".$longRoute."\/?$/", $request)) { // correspond at a define route
                if (sizeof($data["params"]) != 0) { // if no param just stop verification
                    foreach (explode("\/", $longRoute) as $key => $value) { // search parameters
                        if (array_search($value, $this->paramTypes, true)) {
                            $valueParam = explode("/", $request)[intval($key)];

                            $params[$data["params"][sizeof($params)]] = $valueParam;
                        }
                    }
                }
                $result = $data["controller"];
                break;
            }
        }

        return $result == "" ? false : [
            "controller" => $result,
            "params" => $params,
        ];
    }
}
