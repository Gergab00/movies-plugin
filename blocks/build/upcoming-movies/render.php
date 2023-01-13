<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

extract($attributes);

?>

<section <?php echo get_block_wrapper_attributes(['class' => 'row']); ?> data-block="upcoming-movies">
	<?php

$organized_movies = $this->organize_by_date($this->getMovies());

foreach ($organized_movies as $month_year => $movies) {
    foreach ($movies as $movie) {
        //print_r($movie);
        ?>
		<div class="col-md-4 pb-2">
        <div class="card rounded-4 text-bg-dark">
            <img src="<?php echo $movie['poster_path']; ?>" class="card-img-top rounded-4">
            <div class="card-body" style="height: 100%;">
                <h5 class="card-title" style="height: 6rem;"><?php echo $movie['original_title']; ?></h5>
                <p style="height: 6rem;">
                <?php
                echo 'Genres: ';
                foreach ($movie['genres'] as $genre_id) {
                    echo '<span class="badge text-bg-light">' . $genre_id . '</span> ';
                }
                ?>
                </p>
                <a href="<?php echo $this->create_movie_link($movie); ?>" class="btn btn-primary">See more details</a>
            </div>
			<div class="card-footer text-muted">
    			<?php echo $month_year; ?>
  			</div>
        </div>
		</div>
        <?php
    }
}
?>
</section>