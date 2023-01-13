<?php
/**
 *
 * Template Name: Movie Template
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

?>

<div class="wrapper" id="page-movies-wrapper">

    <div class="container-lg" id="content" tabindex="-1">

        <div class="row">

            <?php
if (!isset($_GET['movie_id'])) {
    get_sidebar();
}
?>

            <main class="site-main" id="main">

                <?php
if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];
    // Code to make the API request using $movie_id as parameter
    $movie_info = new MovieInfo(API_KEY);
    $details    = $movie_info->get_movie_details($movie_id);
    $movie_info->get_movie_trailer($details);
    ?>


                <div class="card my-3 bg-transparent rounded-0 border border-0">
                    <div class="row g-0">
                        <div class="col-md-7">
                            <div class="card-body">
                                <h1 class="display-5 fw-bold"><?php echo $details['original_title']; ?> <span
                                        class="badge bg-primary text-white fs-5"><?php echo $details['original_language']; ?></span>
                                </h1>
                                <h6 class="movie-release-date"><span>Release Date:</span>
                                    <?php echo date('d/m/Y', strtotime($details['release_date'])); ?></h6>
                                <?php echo $movie_info->get_movie_popularity_html($details); ?>
                                <?php echo $movie_info->get_movie_production_companies_html($details); ?>
                                <p class="card-text"><?php echo $details['overview']; ?></p>
                                <?php echo $movie_info->get_movie_genres_html($details, 'movie-genres', 'genre-label bg-primary text-light'); ?>
                            </div>
                        </div>
                        <div class="col-md-5 d-flex justify-content-center align-items-center">
                            <img src="<?php echo $details['poster_path']; ?>" class="movie-img"
                                alt="<?php echo $movie['original_title']; ?>">
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="card-footer text-muted">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#trailerModal">
                                Watch Trailer
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="trailerModal" tabindex="-1" aria-labelledby="trailerModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="trailerModalLabel">Trailer of
                                                <?php echo $details['original_title']; ?></h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body d-flex justify-content-center align-items-center">
                                            <iframe id="movieTrailer" width="560" height="315"
                                                src="<?php echo $details['trailer'];?>" title="YouTube video player"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-0">
                    <nav>
  <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-cast-tab" data-bs-toggle="tab" data-bs-target="#nav-cast" type="button" role="tab" aria-controls="nav-cast" aria-selected="true">Cast</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
    <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false" disabled>Disabled</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-cast" role="tabpanel" aria-labelledby="nav-cast-tab" tabindex="0">
    <div class="row">  <?php
     $cast = array_slice($movie_info->get_movie_cast($details), 0, 9);
    foreach ($cast as $cast_member) {
        ?>
    <div class="col-md-4">
        <div class="card m-4">
            <img src="<?php echo 'https://image.tmdb.org/t/p/w500' . $cast_member['profile_path']; ?>" class="card-img" alt="...">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h5 class="card-title bg-info bg-gradient bg-opacity-75 mb-0 p-2"><a href="<?php echo create_actor_link($cast_member); ?>"><?php echo $cast_member['name']; ?></a></h5>
                <p class="card-text bg-info bg-gradient bg-opacity-50 p-2"><?php echo $cast_member['character']; ?></p>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
  </div>
  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
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
