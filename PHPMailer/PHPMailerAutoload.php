<?php

function PHPMailerAutoload($classname)
{
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    // SPL autoloading was introduced in PHP 5.1.2
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('PHPMailerAutoload', true, true);
    } else {
        spl_autoload_register('PHPMailerAutoload');
    }
} else {
    // Use an anonymous function for versions of PHP earlier than 5.1.2
    spl_autoload_register(function ($classname) {
        PHPMailerAutoload($classname);
    });
}
