<?php

namespace wordpress\mvc_structure;

class App
{

    private static $instance = null;

    // Method to get the single instance of the class
    public static function getInstance() {

        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * In this method you can define what you want to run when website is loaded. Of course the plugin should be activated.
     * @return void
     */
    public function init()
    {

        /*
        |--------------------------------------------------------------------------
        | Register the menu
        |--------------------------------------------------------------------------
        */
        add_action('admin_menu', function (){

            add_menu_page(
                __('Wordpress MVC', WIZBAN_TRANSLATE_KEY),
                __('Wordpress MVC', WIZBAN_TRANSLATE_KEY),
                'manage_options',
                'Wordpress-mvc-structure',
                function (){
                    // Create your Theme's Setting panel and return that here
                },
                'dashicons-admin-settings',
                50
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Require the front libraries
        |--------------------------------------------------------------------------
        */
        $this->defineScripts();
    }

    /**
     * We define all routes here that accessible in the wp-json endpoints.
     * @return void
     */
    public function registerRoutes()
    {

    }

    /**
     * We define all assets here for enqueue and localize.
     * @return void
     */
    public function defineScripts()
    {
        $assetPath = WPMVCST_ROOT_ASSETS;

        //Frontend loading assets
        add_action('wp_enqueue_scripts', function ($hook) use($assetPath) {

            if (!is_admin()) {

                wp_enqueue_style('frontend-css', $assetPath . 'frontend.min.css');
                wp_enqueue_script('frontend-js', $assetPath . 'frontend.min.js', array('jquery'), '1.0', true);

                wp_localize_script('frontend-js', 'wordpressMVCStructureObject', array(
                    'rest_url' => esc_url_raw(rest_url()),
                    'nonce' => wp_create_nonce('wp_rest')
                ));
            }
        });
    }
}
