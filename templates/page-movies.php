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
    <button class="nav-link" id="nav-reviews-tab" data-bs-toggle="tab" data-bs-target="#nav-reviews" type="button" role="tab" aria-controls="nav-reviews" aria-selected="false">Reviews</button>
    <button class="nav-link" id="nav-similar-tab" data-bs-toggle="tab" data-bs-target="#nav-similar" type="button" role="tab" aria-controls="nav-similar" aria-selected="false">List of similar Movies</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-cast" role="tabpanel" aria-labelledby="nav-cast-tab" tabindex="0">
    <div class="row">  <?php
     $cast = array_slice($movie_info->get_movie_cast($details), 0, 9);
    foreach ($cast as $cast_member) {
        $profile = isset($cast_member['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $cast_member['profile_path'] : 'https://via.placeholder.com/300x450';
        ?>
    <div class="col-md-4">
        <div class="card m-4">
            <img src="<?php echo $profile; ?>" class="card-img" alt="...">
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
  <div class="tab-pane fade" id="nav-reviews" role="tabpanel" aria-labelledby="nav-reviews-tab" tabindex="0">
    <div class="row">
    <?php
    $reviews = array_slice($movie_info->get_movie_reviews($details), 0, 9);
    foreach ($reviews as $review) {
        ?>
    <div class="col-md-4">
        <div class="card m-4">
            <div class="card-body bg-dark">
                <h5 class="card-title"><?php echo $review['author']; ?></h5>
                <p class="card-text"><?php echo $review['content']; ?></p>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
  </div>
  <div class="tab-pane fade" id="nav-similar" role="tabpanel" aria-labelledby="nav-similar-tab" tabindex="0">
    <div class="row">
    <?php
    $similar_movies = array_slice($movie_info->get_similar_movies($details), 0, 9);
    foreach ($similar_movies as $similar_movie) {
        ?>
    <div class="col-md-4">
        <div class="card m-4">
            <img src="<?php echo $similar_movie['backdrop_path']; ?>" class="card-img" alt="...">
            <div class="card-img-overlay d-flex flex-column justify-content-end">
                <h5 class="card-title bg-info bg-gradient bg-opacity-75 text-black mb-0 p-2"><a href="<?php echo create_movie_link($similar_movie); ?>"><?php echo $similar_movie['title']; ?></a></h5>
                <p class="card-text bg-info bg-gradient bg-opacity-50 text-black p-2"><?php echo $similar_movie['release_date']; ?></p>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
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
