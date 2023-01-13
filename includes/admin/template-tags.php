<?php

/**
 *
 * Create a link to a actor page
 *
 * @param array $actor Actor data on JSON format
 * @return string Link to a actor page
 *
 */
function create_actor_link($actor)
{
    $options = get_option('movies_plugin_options');
    if (!isset($options['pages_actor'])) {
        return '';
    }
    $page_id = ('' != $options['pages_actor']) ? $options['pages_actor'] : '';

    if ($page_id) {
        $link = add_query_arg('actor_id', $actor['id'], get_permalink($page_id));
        return $link;
    }
    return '';
}

function create_movie_link($movie)
{
    $options = get_option('movies_plugin_options');
    if (!isset($options['pages'])) {
        return '';
    }
    $page_id = ('' != $options['pages']) ? $options['pages'] : '';

    if ($page_id) {
        $link = add_query_arg('movie_id', $movie['id'], get_permalink($page_id));
        return $link;
    }
    return '';
}

function get_bootstrap_images_carousel($images_arr)
{
    if (empty($images_arr)) {
        return '<div>No images available</div>';
    }
    $carousel_html = '<div id="actorImagesCarousel" class="carousel slide" data-bs-ride="true">';
    $carousel_html .= '<div class="carousel-indicators">';
    for ($i = 0; $i < count($images_arr); $i++) {
        $carousel_html .= '<button data-bs-target="#actorImagesCarousel" data-bs-slide-to="' . $i . '"';
        if (0 === $i) {
            $carousel_html .= ' class="active"';
            $carousel_html .= ' aria-current="true"';
        }
        $carousel_html .= ' aria-label="Slide ' . ($i + 1) . '"';
        $carousel_html .= '></button>';
    }
    $carousel_html .= '</div>';
    $carousel_html .= '<div class="carousel-inner">';
    for ($i = 0; $i < count($images_arr); $i++) {
        $carousel_html .= '<div class="carousel-item';
        if (0 === $i) {
            $carousel_html .= ' active';
        }
        $carousel_html .= '">';
        $carousel_html .= '<img src="' . $images_arr[$i] . '" class="d-block w-25 m-auto" alt="Actor Image">';
        $carousel_html .= '</div>';
    }
    $carousel_html .= '</div>';
    $carousel_html .= '<button class="carousel-control-prev" type="button" data-bs-target="#actorImagesCarousel" role="button" data-bs-slide="prev">';
    $carousel_html .= '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
    $carousel_html .= '<span class="sr-only">Previous</span>';
    $carousel_html .= '</button>';
    $carousel_html .= '<button class="carousel-control-next" type="button" data-bs-target="#actorImagesCarousel" role="button" data-bs-slide="next">';
    $carousel_html .= '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
    $carousel_html .= '<span class="sr-only">Next</span>';
    $carousel_html .= '</button>';
    $carousel_html .= '</div>';
    return $carousel_html;
}

function organize_by_title($movies)
{
    $organized_movies = array();
    foreach ($movies as $movie) {
        $title = $movie['original_title'];

        if (!isset($organized_movies[$title])) {
            $organized_movies[$title] = array();
        }

        $organized_movies[$title][] = $movie;
    }

    ksort($organized_movies);

    return $organized_movies;
}

function display_movies_pagination($movies)
{
    //calculate the total number of pages
    $total_pages = ceil(count($movies) / 12);

    //display the pagination buttons
    echo '<nav aria-label="Movies pagination">';
    echo '<ul class="justify-content-center nav nav-pills mb-3" id="pills-tab" role="tablist">';
    for ($i = 1; $i <= $total_pages; $i++) {
        if (1 == $i) {
            echo '<li class="nav-item active">';
            echo '<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-' . $i . '" type="button" role="tab" aria-controls="pills-' . $i . '" aria-selected="true"> ' . $i . '</button>';
        } else {
            echo '<li class="nav-item">';
            echo '<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-' . $i . '" type="button" role="tab" aria-controls="pills-' . $i . '" aria-selected="false"> ' . $i . '</button>';
        }
        echo '</li>';
    }
    echo '</ul>';
    echo '</nav>';

    echo '<div class="tab-content" id="pills-tabContent">';
    $ni     = 0;
    $nf     = 12;
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = (1 == $i) ? "active" : "";
        echo '<div class="tab-pane fade show ' . $active . '" id="pills-' . $i . '" role="tabpanel" aria-labelledby="pills-' . $i . '-tab" tabindex="0">';
        //get the 12 movies for the current page
        $current_movies = array_slice($movies, $ni, $nf);
        //display the movies in a grid using Bootstrap
        echo '<div class="row">';
        foreach ($current_movies as $movie) {
            echo '<div class="col-md-4">';
            echo '<div class="card m-4 bg-dark text-white">';
            echo '<img src="' . $movie['backdrop_path'] . '" class="card-img-top">';
            echo '<div class="card-body" style="height: 100%;">';
            echo '<h5 class="card-title" style="height: 4rem;"><a href="' . create_movie_link($movie) . '">' . $movie['title'] . '</a></h5>';
            echo '<p class="card-text my-2" style="height: 6rem;">';
            echo 'Genres: ';
            foreach ($movie['genre_ids'] as $genre_id) {
                echo '<span class="badge text-bg-light">' . $genre_id . '</span> ';
            }
            echo '</p>';
            echo '<p class="card-text">Release Date: ' . $movie['release_date'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        $ni = $ni + 12;
        $nf = $nf + 12;
        echo '</div>';
    }

    echo '</div>';
}

function display_actors_pagination($actors)
{

    //calculate the total number of pages
    $total_pages = ceil(count($actors) / 12);

    //display the pagination buttons
    echo '<nav aria-label="Actors pagination">';
    echo '<ul class="justify-content-center nav nav-pills mb-3" id="pills-tab" role="tablist">';
    for ($i = 1; $i <= $total_pages; $i++) {
        if (1 == $i) {
            echo '<li class="nav-item active">';
            echo '<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-' . $i . '" type="button" role="tab" aria-controls="pills-' . $i . '" aria-selected="true"> ' . $i . '</button>';
        } else {
            echo '<li class="nav-item">';
            echo '<button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-' . $i . '" type="button" role="tab" aria-controls="pills-' . $i . '" aria-selected="false"> ' . $i . '</button>';
        }
        echo '</li>';
    }
    echo '</ul>';
    echo '</nav>';

    echo '<div class="tab-content" id="pills-tabContent">';
    $ni     = 0;
    $nf     = 12;
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = (1 == $i) ? "active" : "";
        echo '<div class="tab-pane fade show ' . $active . '" id="pills-' . $i . '" role="tabpanel" aria-labelledby="pills-' . $i . '-tab" tabindex="0">';
        //get the 12 actors for the current page
        $current_actors = array_slice($actors, $ni, $nf);
        //display the actors in a grid using Bootstrap
        echo '<div class="row">';
        foreach ($current_actors as $actor) {
            echo '<div class="col-md-4">';
            echo '<div class="card m-4 bg-dark text-white">';
            echo '<img src="' . $actor['profile_path'] . '" class="card-img-top">';
            echo '<div class="card-body" style="height: 100%;">';
            echo '<h5 class="card-title" style="height: 4rem;"><a href="' . create_actor_link($actor) . '">' . $actor['name'] . '</a></h5>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        $ni = $ni + 12;
        $nf = $nf + 12;
        echo '</div>';
    }

    echo '</div>';
}

function sort_array_alphabetically($array)
{
    $param = null;
    if (array_key_exists('name', $array)) {
        $param = 'name';
    } else if (array_key_exists('title', $array)) {
        $param = 'title';
    }

    if (!$param) {
        return $array;
    }

    usort($array, function ($a, $b) use ($param) {
        return strcmp($a[$param], $b[$param]);
    });

    return $array;
}