<?php

namespace usefull;

class Clean
{
    public static function urlClen(string $url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function stringClean(string $url)
    {
        //return filter_has_var($url, FILTER_SANITIZE_STRING);
        return $url; // TODO: a mettre un vrai truc
    }
}