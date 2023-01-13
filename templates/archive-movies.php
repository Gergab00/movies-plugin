<?php
/**
 * Template Name: Movies Archive
 *
 * @package WordPress
 * @subpackage movie-plugin
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>

<div class="wrapper" id="archive-movies-wrapper">

    <main id="archive-movies" class="container-lg">
        
        <div class="row my-4">
            <div class="col-md-9">
            <?php
    $movie_info = new MovieInfo(API_KEY);
$movies     = sort_array_alphabetically($movie_info->get_popular_movies());
if ($movies) {
    // Code to make the API request using $movie_id as parameter
    //print_r($movies);

    echo display_movies_pagination($movies);
} else {
    if (have_posts()): ?>

            <header class="page-header">
                <?php
the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
            </header><!-- .page-header -->

            <?php
/* Start the Loop */
        while (have_posts()):
            the_post();

            /*
             * Include the Post-Type-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
             */
            get_template_part('template-parts/content', get_post_type());

        endwhile;

        the_posts_navigation();

    else:

        get_template_part('template-parts/content', 'none');

    endif;
}
?>
</div>
            <div class="col-md-3">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </main><!-- #main -->
</div>
<?php

get_footer();
