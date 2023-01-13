<?php
/**
 *
 *
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

class UpcomingMovies
{

 private $__movies;

 /**
  * Constructor
  */
 public function init()
 {
  add_action('init', array($this, 'registrer'));
 }

 public function registrer()
 {
  register_block_type(
   __DIR__,
   array(
    /**
     * Render callback function.
     *
     * @param array    $attributes The block attributes.
     * @param string   $content    The block content.
     * @param WP_Block $block      Block instance.
     *
     * @return string The rendered output.
     */
    'render_callback' => function ($attributes, $content, $block) {
     ob_start();
     require_once __DIR__ . '/render.php';
     return ob_get_clean();
    },
   ));
 }

 public function setMovies($movies)
 {
  $this->movies = $movies;
 }

 public function getMovies()
 {
  return $this->movies;
 }

 /**
  *
  * Organize movies by date
  *
  * @param array $movies Movies data on JSON format
  * @return array Movies organized by date
  *
  */
 public function organize_by_date($movies)
 {
  $organized_movies = array();

  foreach ($movies as $movie) {
   $release_date = $movie['release_date'];
   $month_year   = date('F Y', strtotime($release_date));

   if (!isset($organized_movies[$month_year])) {
    $organized_movies[$month_year] = array();
   }

   $organized_movies[$month_year][] = $movie;
  }

  return $organized_movies;
 }

 /**
  *
  * Create a link to a movie page
  *
  * @param array $movie Movie data on JSON format
  * @return string Link to a movie page
  *
  */
 public function create_movie_link($movie)
 {

  $options = get_option('movies_plugin_options');
  $page_id = ('' != $options['pages']) ? $options['pages'] : '';

  if ($page_id) {

   $link = add_query_arg('movie_id', $movie['id'], get_permalink($page_id));
   return $link;
  }
  return '';
 }

}
