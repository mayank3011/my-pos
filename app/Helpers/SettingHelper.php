<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        // Implement your settings logic (database/config file)
        // Example using config:
        return config("settings.$key", $default);
    }
}
