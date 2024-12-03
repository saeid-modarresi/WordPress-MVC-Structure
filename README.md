# WordPress-MVC-Structure
This repository offers a Model-View-Controller (MVC) framework tailored for WordPress projects. It provides a solid foundation to help developers organize and structure WordPress themes and plugins using the MVC design pattern.

## Intorudction
we’ll go over the practical steps to set up MVC for your WordPress projects. We’ll cover:
- Directory Hierarchy: How to set up a clear, organized directory structure for your WordPress project.
- Model Layer: How to create the Model layer to handle data and business logic, including how to interact with the WordPress database.
- View Layer: How to create views that focus on displaying the user interface while keeping business logic separate.
- Controller Layer: How controllers manage user input and handle interactions between the Model and View.
- Integration with WordPress: How to make the MVC structure work with WordPress hooks, actions, and filters.
By the end, you’ll have a solid understanding of how to use MVC in your WordPress projects to make your code more organized, maintainable, and scalable.

## Install
After downloading and activating the theme, follow these steps to set up your development environment:
Install Dependencies: First, navigate to your project directory and run the following commands to install the required dependencies:

```
composer install   # Installs PHP dependencies (including Illuminate Database)
npm install        # Installs JavaScript dependencies (Grunt, Gulp, Webpack, etc.)
```
* Configure Environment: Make sure that your environment is properly configured for development. This includes setting up the necessary configurations in composer.json and package.json for autoloading, task automation, and dependencies management.

## Concepts:
- **PSR-4** is a standard for autoloading in PHP. It defines how classes should be stored and named in files, making it easier to organize code. According to PSR-4, the class name should match the folder structure, so Composer can automatically find and load the class without extra effort. This helps keep code consistent and simplifies the process of adding new classes.

- **Illuminate/Database** is a library for PHP that makes working with databases easier. It provides simple tools for tasks like getting, adding, updating, and deleting data. Instead of writing raw SQL, you can use easy-to-understand methods to interact with the database.
With Illuminate/Database, you can treat your database tables as classes, which makes managing data simpler. It also includes helpful features like building queries, making changes to the database structure, and handling relationships between tables. All of this helps make database tasks faster and easier.

- **NPM** stands for Node Package Manager, and it’s a tool used to manage packages (libraries or tools) for JavaScript projects. It allows developers to easily install, update, and share code libraries that help with development tasks.
With NPM, you can install packages from a large collection of pre-built tools and libraries. It also manages dependencies, ensuring that the right versions of libraries are used in your project. NPMhelps automate and streamline the process of adding and updating code, making development more efficient.

- To streamline your development process, you can use a **task runner** like Grunt, Gulp or Webpack to automate tasks such as compiling Sass, minifying JavaScript, and optimizing images. First, you need to create and configure a package.json file to manage these tasks and dependencies.

- We created an **instance** of the App class using the getInstance() method. This is likely part of a singleton pattern, meaning only one instance of the App class will be created.


---
## Creating the main files:
In WordPress, the functions.php file is where you add custom functions and modify how your theme or plugin works. It’s the first step in setting up the backend logic for the theme.
The Functions.php will be:

```
<?php

/*
|--------------------------------------------------------------------------
| Define root path
|--------------------------------------------------------------------------
*/
if (!defined("DS")) define("DS", DIRECTORY_SEPARATOR);
define("WPMVCST_ABSPATH", get_template_directory() . DS);
define("WPMVCST_BASENAME", basename(WPMVCST_ABSPATH));
define("WPMVCST_ROOT_DIR", get_template_directory());
define("WPMVCST_ROOT_URL", trailingslashit(get_template_directory_uri()));
define("WPMVCST_ROOT_ASSETS", trailingslashit(WPMVCST_ROOT_URL . "assets" . DS));
define("WPMVCST_ROOT_TPL", trailingslashit(WPMVCST_ABSPATH . "templates" . DS));
define("WPMVCST_TRANSLATE_KEY", 'wordpress-mvc-text-domain');

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
```

We created an instance of the App class using the getInstance() method. This is likely part of a singleton pattern, meaning only one instance of the App class will be created.

The singleton pattern is a way to make sure a class has only one instance and provides a simple way to access that instance from anywhere in the code.

Here’s how it works:

- The class has a private constructor so it can’t be created directly from outside.
It has a static property to store the one instance of the class.
A static method (like getInstance()) checks if the instance already exists. If not, it creates it and returns it.
This pattern is useful when you need just one instance of a class, like a database connection or a configuration manager.

The App.php will be:

```
<?php

namespace wordpress\mvc_structure;

use wordpress\mvc_structure\Controllers\Frontend\PostsController;
use wordpress\mvc_structure\Src\BaseApp;

class App extends BaseApp
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
 
    }
}
```


- The init() is the main method that defines the actions to take when the website is 
loaded. It can includes all your setting up.

- The registerRoutes() method is used to define REST API routes that are accessible through WordPress's wp-json endpoints. In this example, it creates a route for retrieving posts via the PostsController::class and its list method, You can use the auth (last param in the createRoute to authenticate users)

This class is an essential part of setting up the MVC structure for a WordPress site, managing routes, assets, and admin settings in a clean and organized manner.

## Create Controllers and models

PostsController is a sample from our controllers that extends from BaseController. 
It handles the logic for fetching WordPress posts, either all posts or a specific post based on the post_id parameter. It uses custom models and a query builder to fetch data and then returns a structured response.

Also you can see here, how we can use the ORM to connect to DB and get the tables.


```

<?php

namespace wordpress\mvc_structure\Controllers\Frontend;

use wordpress\mvc_structure\Models\Post;
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
        ];

        if(empty($postID)){

            $posts = get_posts($args);

        }else{

            // Using ORM to fetch a post by ID
            $posts = Post::query()->where("ID", $postID)->first();
            $posts = !empty($posts) ? $posts->toArray() : [];
        }
     
        // Return the response
        return $this->getResponse(true, 200, $posts);
    }
}
```

The Post model defines the structure for handling post-related data:

```
<?php

namespace wordpress\mvc_structure\Models;

use wordpress\mvc_structure\Src\Abstracts\Model;

class Post extends Model
{
    public static $postType = "post";
    public static $taxonomy = "category";
}
```

Finally, you can retrieve data from the controller and display it in your template, which demonstrates how we can separate the different layers of programming using the MVC pattern in this theme. By organizing the application into distinct Model, View, and Controller layers, we can ensure better maintainability and scalability.

For example, by calling the following route, you can fetch the list of posts via the WordPress REST API and then display it in your template using JavaScript. Alternatively, you can directly call the list from the PostsController and integrate it into your template:

```
http://localhost/wordpress/wp-json/wordpress/mvc_structure/v1/frontend/posts

```
This approach helps to clearly separate concerns: the Controller handles the logic, the Model manages the data, and the View renders the user interface. Keep in mind that this is just a sample project to demonstrate the MVC structure, and you can easily extend it to include additional features and improvements. The code can be further optimized for a cleaner, more modular, and scalable architecture.