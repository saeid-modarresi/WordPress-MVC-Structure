<?php

namespace wordpress\mvc_structure\Src\Hooks;

trait General
{

    public function initRquiredMethods()
    {
        self::initSupportItems();
        self::registerMenu();
        self::createThumbnails();
    }

    public static function initSupportItems()
    {
        add_action('after_setup_theme', function(){
            add_theme_support('woocommerce');
            add_theme_support( 'title-tag' );
            add_theme_support( 'wp-block-styles' );
            add_theme_support( 'editor-styles' );
        });

        if ( function_exists('add_theme_support') ) add_theme_support('post-thumbnails');
    }

    public static function registerMenu()
    {
        add_action('init', function(){
            // for example:
            register_nav_menu('main-menu', __('Main menu', WPMVCST_TRANSLATE_KEY));
            register_nav_menu('mobile-menu', __('Mobile menu', WPMVCST_TRANSLATE_KEY));
        });
    }

    public static function createThumbnails()
    {
        add_action( 'init', function (){
            // for example:
            add_image_size( '1280x340', 1280, 340, true );
        });
    }
}