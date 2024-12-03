<?php

namespace wordpress\mvc_structure\Src\Abstracts;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{

    public function __construct()
    {
        global $wpdb;

        $capsule = new Capsule();

        $capsule->addConnection([
            "driver" => "mysql",
            "host"   => DB_HOST,
            "database" => DB_NAME,
            "username" => DB_USER,
            "password" => DB_PASSWORD,
            'charset'  => DB_CHARSET,
            'prefix'   => $wpdb->prefix
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}