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
   if(!isset($options['pages_actor'])) {
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
   if(!isset($options['pages'])) {
    return '';
   }
   $page_id = ('' != $options['pages']) ? $options['pages'] : '';
 
   if ($page_id) {
 
    $link = add_query_arg('movie_id', $movie['id'], get_permalink($page_id));
    return $link;
   }
   return '';
  }

  function get_bootstrap_images_carousel($images_arr) {
    if (empty($images_arr)) {
        return '<div>No images available</div>';
    }
    $carousel_html = '<div id="actorImagesCarousel" class="carousel slide" data-bs-ride="true">';
    $carousel_html .= '<div class="carousel-indicators">';
    for ($i = 0; $i < count($images_arr); $i++) {
        $carousel_html .= '<button data-bs-target="#actorImagesCarousel" data-bs-slide-to="' . $i . '"';
        if ($i === 0) {
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
        if ($i === 0) {
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
