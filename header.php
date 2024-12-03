<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$faviconURL = get_site_icon_url() ? get_site_icon_url() : get_template_directory_uri() . "/favicon.png";

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="keywords" content=""/>
    <link rel="icon" type="image/png" href="<?php echo $faviconURL ?>"/>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;900&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr' ?>">
<?php function_exists('wp_body_open') ? wp_body_open() : do_action('wp_body_open'); ?>