<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();
?>
<section class="page">
    <div class="content-area">
        <?php
        the_content();

        if ( comments_open() || get_comments_number() ) {
            comments_template();
        }
        ?>
    </div>
</section>
<?php get_footer(); ?>
