<?php
/**
 *
 * Template Name: Actor Template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();


?>

<div class="wrapper" id="page-actor-wrapper">

    <div class="container-lg" id="content" tabindex="-1">

        <div class="row">

            <?php
if (!isset($_GET['actor_id'])) {
    // Do the left sidebar check and open div#primary.
    get_sidebar();
}
?>

            <main class="site-main" id="main">

                <?php
if (isset($_GET['actor_id'])) {
    $actor_id = $_GET['actor_id'];
    // Code to make the API request using $actor_id as parameter
    $actor_info = new ActorInfo(API_KEY);
    $details    = $actor_info->get_actor_details($actor_id);

    ?>


                <div class="card my-3 bg-transparent rounded-0 border border-0">
                    <div class="row g-0">
                        <div class="col-md-7">
                            <div class="card-body">
                                <h1 class="display-5 fw-bold">
                                    <?php echo $details['name']; ?>
                                </h1>
                                <h6 class="movie-release-date">
                                    <span>Birthday:</span>
                                    <?php echo date('d/m/Y', strtotime($details['birthday'])); ?>
                                     - 
                                    <span>Place of Birth:</span>
                                    <?php echo $details['place_of_birth']; ?> 
                                </h6>
                                <?php echo $actor_info->get_actor_popularity_html($details); ?>
                                <p class="card-text"><?php echo $details['biography']; ?></p>
                                <?php echo $actor_info->get_actor_homepage_html($details); ?>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex justify-content-center align-items-center">
                            <img src="<?php echo $details['profile_path']; ?>" class="movie-img"
                                alt="<?php echo $details['name']; ?>">
                        </div>
                    </div>
                    <div class="row g-0">
                    <nav>
  <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-movies-tab" data-bs-toggle="tab" data-bs-target="#nav-movies" type="button" role="tab" aria-controls="nav-movies" aria-selected="true">Movies</button>
    <button class="nav-link" id="nav-photos-tab" data-bs-toggle="tab" data-bs-target="#nav-photos" type="button" role="tab" aria-controls="nav-photos" aria-selected="false">Photos</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-movies" role="tabpanel" aria-labelledby="nav-movies-tab" tabindex="0">
    <div class="row">  <?php
     $movies = array_slice($actor_info->get_actor_movies($details), 0, 9);
    foreach ($movies as $movie) {
        if ($movie['backdrop_path'] == null) {
            $movie['backdrop_path'] = 'https://via.placeholder.com/370x200';
        }
        ?>
    <div class="col-md-4">
        <div class="card m-4">
            <img src="<?php echo $movie['backdrop_path']; ?>" class="card-img" alt="...">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h5 class="card-title bg-info bg-gradient bg-opacity-75 mb-0 p-2 text-black"><a href="<?php echo create_movie_link($movie); ?>"><?php echo $movie['title']; ?></a></h5>
                <p class="card-text bg-info bg-gradient bg-opacity-50 p-2 text-black"><?php echo $movie['character']; ?></p>
                <p class="card-text bg-info bg-gradient bg-opacity-50 p-2 text-black">Release on: <?php echo $movie['release_date']; ?></p>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
  </div>
  </div>
  <div class="tab-pane fade" id="nav-photos" role="tabpanel" aria-labelledby="nav-photos-tab" tabindex="0">
    <?php
      echo get_bootstrap_images_carousel($actor_info->get_actor_images($details));
    ?>
  </div>
</div>
                    </div>
                </div>
                <?php
} else {
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('loop-templates/content', 'page');

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        }
    }
}

?>

            </main>

        </div><!-- .row -->

    </div><!-- #content -->

</div><!-- #page-wrapper -->

<?php
get_footer();
