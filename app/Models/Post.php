<?php

namespace wordpress\mvc_structure\Models;

use wordpress\mvc_structure\Src\Abstracts\Model;

class Post extends Model
{
    public static $postType = "post";
    public static $taxonomy = "category";
}