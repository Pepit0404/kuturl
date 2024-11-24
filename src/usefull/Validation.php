<?php

namespace usefull;

class Validation
{
    public static function shortCutValidation(string $url) : bool
    {
        $minSize = 3;
        $maxSize = 255;
        $regex = '/^[a-zA-Z0-9]+$/';
        return !empty($url) && $minSize <= strlen($url) && strlen($url) < $maxSize && preg_match($regex, $url);
    }

    public static function URLValidation(string $url) : bool{
        $maxSize = 255;
        $regex = "/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/";
        return !empty($url) && strlen($url) < $maxSize && preg_match($regex, $url);
    }

    public static function EntryValidation(string $short, string $target) : array
    {
        $error = [];
        if ($short != null && $target != null) {
            $short = Clean::stringClean($short);
            $target = Clean::urlClen($target);
            if (! Validation::URLValidation($target)) {
                $error [] = "The orignal links isn't valid";
            } else if (! Validation::shortCutValidation($short)) {
                $error [] = "The desired link must be between 3 and 255 letters or numbers";
            }
        }

        return $error;
    }

}