<?php

/*
|--------------------------------------------------------------------------
| Define root path
|--------------------------------------------------------------------------
*/
if (!defined("DS")) define("DS", DIRECTORY_SEPARATOR);

// Define constants for the theme
define("WPMVCST_ABSPATH", get_template_directory() . DS);
define("WPMVCST_BASENAME", basename(WPMVCST_ABSPATH));
define("WPMVCST_ROOT_DIR", get_template_directory());
define("WPMVCST_ROOT_URL", trailingslashit(get_template_directory_uri()));
define("WPMVCST_ROOT_ASSETS", trailingslashit(WPMVCST_ROOT_URL . "assets" . DS));
define("WPMVCST_ROOT_TPL", trailingslashit(WPMVCST_ABSPATH . "templates" . DS));

/*
|--------------------------------------------------------------------------
| Load autoload then Init the core
|--------------------------------------------------------------------------
*/
$autoloadPath = __DIR__ . "/vendor/autoload.php";

if (file_exists($autoloadPath)) {

    require_once $autoloadPath;

    if (class_exists(wordpress\mvc_structure\App::class)) {

        $app = \wordpress\mvc_structure\App::getInstance();
        $app->init();
    }
}