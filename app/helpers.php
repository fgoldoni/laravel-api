<?php

const DS = DIRECTORY_SEPARATOR;

if (!function_exists('ds')) {
    function ds()
    {
        return DIRECTORY_SEPARATOR;
    }
}
