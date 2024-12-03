<?php

namespace wordpress\mvc_structure\Src\Traits;

trait Routes
{

    /**
     * In this method, we handled the rest routes.
     * @param string $namespace It's the namespace of App.php file.
     * @param string $method It's the method of call http request GET/POST/PUT/DELETE
     * @param int    $version It's the version of our api endpoints.
     * @param string $route It's the baseuri of route.
     * @param string $class It detects which class should be new.
     * @param string $callback This is the method of class that should be called.
     * @param string $capability The capability used for check permission to the endpoints.
     * @return void
     */
    public function createRoute($method, $version, $route, $class, $callback, $capability, $auth)
    {

        add_action( 'rest_api_init', function () use($method, $version, $route, $class, $callback, $capability, $auth){

            register_rest_route( $this->namespace . "/{$version}", "/{$route}", array(
                'methods'  => $method,
                'callback' => function (\WP_REST_Request $request) use($class, $callback){

                    /*
                    |--------------------------------------------------------------------------
                    | New class and run related method
                    |--------------------------------------------------------------------------
                    */
                    $object = new $class($request);
                    return $object->$callback($request);
                },
                'permission_callback' => function(\WP_REST_Request $request) use($capability, $auth){

                    return $this->checkAccessEndpoint($request, $capability, $auth);
                }
            ));
        });
    }

    /**
     * By this method we checked the user access to this endpoint
     * @param object $request It's the WP_REST_Request
     * @param string $capability It endpoint capability
     * @param bool $auth This param can turn on or off the check permission
     * @author Saeid.modarresi.dev@gmail.com
     * @return bool|WP_Error return the access of user to this endpoint
     */
    public function checkAccessEndpoint( object $request, string $capability, bool $auth) : bool|WP_Error
    {

        /*
        |--------------------------------------------------------------------------
        | Get the requirements
        |--------------------------------------------------------------------------
        */
        if (!$auth) {

            return true;

        }else{

            $user = $this->getUserDataByRequest($request);
            $userID = !empty($user->ID) ? $user->ID : "";
            if (empty($user)) return false;
            if(is_super_admin($userID)) return true;

            /*
            |--------------------------------------------------------------------------
            | Check user access to capability and return response
            |--------------------------------------------------------------------------
            */
            $result = user_can($userID, $capability);
            return !empty($result) ? true : \WP_Error(403 , "You don't have enough access!");
        }
    }

    /**
     * Get the user data by request
     * @param object $request It's the WP_REST_Request.
     * @author Saeid.modarresi.dev@gmail.com
     * @return object|bool Object of user or false
     */
    public function getUserDataByRequest( object $request ) : object|bool
    {

        /*
        |--------------------------------------------------------------------------
        | Detect authorization method
        |--------------------------------------------------------------------------
        */
        $XWPNonce = $request->get_header("x_wp_nonce");

        /*
        |--------------------------------------------------------------------------
        | Authorization by WP Nonce
        |--------------------------------------------------------------------------
        */
        if (!empty($XWPNonce)){

            $verified = wp_verify_nonce( $XWPNonce, 'wp_rest' );

            if ($verified){

                $userID = get_current_user_id();
                return !empty($userID) ? get_userdata($userID) : false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Authorization by Bearer token
        |--------------------------------------------------------------------------
        */
        $accessToken = $request->get_header("authorization");
        $accessToken = str_contains($accessToken, "Bearer") ? str_replace("Bearer ", "", $accessToken) : "";

        // @TODO Handle the authorization token
        return false;
    }
}