<?php

namespace wordpress\mvc_structure\Frontend\Controllers;

use Wizban\Theme\Models\Post;
use wordpress\mvc_structure\Src\Abstracts\BaseController;

class PostsController extends BaseController
{

    public function list($request)
    {

        //Get params:
        $postID = $request->get_param("post_id");

        $args = [
            'post_type'      => Post::$postType,
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ];

        if(empty($postID)){

            $posts = get_posts($args);

        }else{

            $posts = Post::query()->where("ID", $postID)->first();
        }

        // Return the response
        return $this->getResponse(true, 200, $posts);
    }
}