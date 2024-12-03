<?php

namespace wordpress\mvc_structure\Traits;

use WP_REST_Response;

trait Response
{
    /**
     * This method handle the response
     * @param bool $responseStatus It's response status and can be true/false
     * @param int $responseCode It's response code for example 200/400.
     * @param array $response It's the response.
     * @param array|null $responseFields The array of fields that can shown.
     * @param array|null $paginate The response should be paginated or not.
     * @return mixed Response based on the WP_REST_Response.
     */
    public function getResponse(bool $responseStatus, int $responseCode, array $response, ?array $responseFields = [], ?array $paginate = [])
    {

        /*
        |--------------------------------------------------------------------------
        | Return the error
        |--------------------------------------------------------------------------
        */
        if($responseStatus === false || $responseCode > 299){

            $response = [
                "success" => $responseStatus,
                "error" => !empty($response["error"]) ? $response["error"] : "There is an unknown error!"
            ];

            /*
            |--------------------------------------------------------------------------
            | Return the response
            |--------------------------------------------------------------------------
            */
            $response = new WP_REST_Response($response, $responseCode);

            return $response;
        }

        /*
        |--------------------------------------------------------------------------
        | Get the response when paginate needed and without pagination
        |--------------------------------------------------------------------------
        */
        if (!empty($paginate)){

            $response = [
                "success" => $responseStatus,
                "data" => $response,
                "info" => $this->paginate($response, $paginate["limit"], $paginate["page"], $paginate["offset"], $paginate["total"])
            ];

        }else{

            $response = [
                "success" => $responseStatus,
                "data" => $response
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Return the response
        |--------------------------------------------------------------------------
        */
        $response = new WP_REST_Response($response, $responseCode);

        return $response;
    }

    /**
     * This method can paginate the data for index.
     * @param array $response It's the response.
     * @param int $page Page to get from response.
     * @param int $limit Limit to get from response.
     * @param int $offset Offset to get from response.
     * @param int $total Total of response array.
     * @return array Paginated response.
     */
    public function paginate(array $response, int $limit, int $page, int $offset, int $total) : array
    {
        /*
        |--------------------------------------------------------------------------
        | Paginate info
        |--------------------------------------------------------------------------
        */
        $totalPages = ceil(($total/$limit));

        return array(
            "current_page"  => $page,
            "next_page"     => (($page + 1) <= $totalPages) ? $page+1 : $page,
            "previous_page" => $page == 1 ? 1 : $page - 1,
            "from"          => $offset + 1,
            "per_page"      => $limit,
            "to"            => (($offset + $limit) < $total) ? ($offset + $limit +1) : $total,
            "total"         => $total,
            "total_page"    => $totalPages
        );
    }
}