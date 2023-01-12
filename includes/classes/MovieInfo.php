<?php
/**
 *
 * MovieInfo class
 *
 * This class is used to retrieve movie information from the MovieDB API.
 * @class MovieInfo
 * @constructor
 * @param {string} api_key - The API key to use when making requests to the MovieDB API.
 * @param {string} api_url - The base URL to use when making requests to the MovieDB API.
 * @param {string} page - Specify which page to query (min:1 - max 10000000).
 * @param {string} language - Value to display translated data for the fields that support it.
 * @param {string} region - Specify a ISO 3166-1 code to filter release dates. Must be uppercase.
 *
 */
class MovieInfo
{
 private $api_key  = 'fb8b718901d5d692f48f10bd2e088dee';
 private $api_url  = 'https://api.themoviedb.org/3/movie/upcoming';
 private $page     = 1;
 private $language = 'en-US';
 private $region   = 'US';

 /**
  *
  * Constructor for the MovieInfo class.
  * @method __construct
  * @param {string} api_key - The API key to use when making requests to the MovieDB API.
  * @param {string} api_url - The base URL to use when making requests to the MovieDB API.
  *
  */
 public function __construct($api_key, $api_url = 'https://api.themoviedb.org/3/movie/upcoming')
 {
  $this->api_key = $api_key;
  $this->api_url = $api_url;
 }

 /**
  *
  * Retrieves a list of movies from the MovieDB API.
  * @method getMovies
  * @return {array} An array of movie IDs.
  *
  */
 public function getMovies()
 {

  $query_params = array(
   'api_key'  => $this->api_key,
   'language' => $this->language,
   'page'     => $this->page,
   'region'   => $this->region,
  );

  $url = add_query_arg($query_params, $this->api_url);

  $response = wp_remote_get($url);

  if (is_wp_error($response)) {
   return array();
  }

  $data = json_decode(wp_remote_retrieve_body($response), true);

  $movies = array();

  if (!empty($data['results'])) {
   $results = array_slice($data['results'], 0, 10);
   foreach ($results as $result) {
    array_push($movies, $result['id']);
   }
  }

  return $movies;
 }

 /**
  *
  * Retrieves movie details for the given movie ID.
  * @param {number} movie_id - The ID of the movie to retrieve.
  * @return {array} An array containing the movie details.
  *
  */
 public function get_movie_details($movie_id)
 {
  $movie_details       = array();
  $movie_details['id'] = $movie_id;

  $endpoint     = "https://api.themoviedb.org/3/movie/$movie_id";
  $query_params = array(
   'api_key' => $this->api_key,
  );

  $url      = add_query_arg($query_params, $endpoint);
  $response = wp_remote_get($url);

  if (is_wp_error($response)) {
   return $movie_details;
  }

  $data = json_decode(wp_remote_retrieve_body($response), true);

  $movie_details['original_title'] = isset($data['original_title']) ? $data['original_title'] : '';
  $movie_details['poster_path']    = isset($data['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $data['poster_path'] : '';
  $movie_details['genres']         = array();
  if (!empty($data['genres'])) {
   foreach ($data['genres'] as $genre) {
    $movie_details['genres'][] = $genre['name'];
   }
  }
  $movie_details['overview']             = isset($data['overview']) ? $data['overview'] : '';
  $movie_details['production_companies'] = array();
  if (!empty($data['production_companies'])) {
   foreach ($data['production_companies'] as $company) {
    $movie_details['production_companies'][] = $company['name'];
   }
  }
  $movie_details['release_date']      = isset($data['release_date']) ? $data['release_date'] : '';
  $movie_details['original_language'] = isset($data['original_language']) ? $data['original_language'] : '';
  $movie_details['popularity']        = isset($data['popularity']) ? $data['popularity'] : 0;

  return $movie_details;
 }

 /**
  *
  * Gets the trailer for a movie and adds it to the movie details array.
  * @param {Array} $movie_details - The array containing the movie details.
  * @return {void}
  *
  */
 public function get_movie_trailer(&$movie_details)
 {
  if (!isset($movie_details['id'])) {
   return;
  }

  $endpoint     = "https://api.themoviedb.org/3/movie/{$movie_details['id']}/videos";
  $query_params = array(
   'api_key'  => $this->api_key,
   'language' => $this->language,
  );

  $url      = add_query_arg($query_params, $endpoint);
  $response = wp_remote_get($url);

  if (is_wp_error($response)) {
   return;
  }

  $data = json_decode(wp_remote_retrieve_body($response), true);

  if (!empty($data['results'])) {
   foreach ($data['results'] as $video) {
    if ('Trailer' === $video['type'] && 'YouTube' === $video['site']) {
     $movie_details['trailer'] = "https://www.youtube.com/embed/{$video['key']}";
     break;
    }
   }
  }
 }

 /**
  *
  * Gets the HTML for the movie genres.
  * @param {Array} $movie - The array containing the movie details.
  * @param {string} $genres_class - The class to use for the genres container.
  * @param {string} $genre_label_class - The class to use for the genre labels.
  * @return {string} The HTML for the movie genres.
  *
  */
 public function get_movie_genres_html($movie, $genres_class = 'movie-genres', $genre_label_class = 'genre-label')
 {
  $genres     = $movie['genres'];
  $genre_html = '<div class="' . $genres_class . '">';
  foreach ($genres as $genre) {
   $genre_html .= '<span class="' . $genre_label_class . '">' . $genre . '</span>, ';
  }
  $genre_html = rtrim($genre_html, ', ');
  $genre_html .= '</div>';
  return $genre_html;
 }

/**
 *
 * Gets the HTML for the movie popularity.
 * @param {Array} $movie - The array containing the movie details.
 * @param {string} $stars_class - The class
 * @return {string} The HTML for the movie popularity.
 *
 */
 public function get_movie_popularity_html($movie, $stars_class = 'stars')
 {
  if (!isset($movie['popularity'])) {
   return '<div>No popularity information available</div>';
  }
  $popularity = intval($movie['popularity'] / 10);
  $star_html  = '<div class="p-2 ' . $stars_class . ' popularity-' . $popularity . '">';
  for ($i = 0; $i < 5; $i++) {
   $star_html .= '<span class="star"></span>';
  }
  $star_html .= '</div>';
  return $star_html;
 }

/**
 *
 * Gets the HTML for the movie production companies.
 * @param {Array} $movie - The array containing the movie details.
 * @param {string} $companies_class - The class to use for the production companies container.
 * @return {string} The HTML for the movie production companies.
 *
 */
 public function get_movie_production_companies_html($movie, $companies_class = 'production-companies text-info text-opacity-50')
 {
  if (isset($movie['production_companies'])) {
   $companies_html = '<div class="' . $companies_class . '">';
   $count          = 0;
   $total          = count($movie['production_companies']);
   foreach ($movie['production_companies'] as $company) {
    $companies_html .= '<div class="production-company">' . $company . '</div>';
    if (++$count < $total) {
     $companies_html .= '-';
    }
   }
   $companies_html .= '</div>';
   return $companies_html;
  } else {
   return '<div>No production company information available</div>';
  }
 }

/**
 * Retrieves movie cast information for the given movie ID.
 * @param {array} $movie - The movie array containing the ID of the movie to retrieve.
 * @return {array} An array containing the movie cast information.
 *
 */
 public function get_movie_cast($movie)
 {
  if (!isset($movie['id'])) {
   return array();
  }

  $movie_cast       = array();
  $movie_cast['id'] = $movie['id'];

  $endpoint     = "https://api.themoviedb.org/3/movie/{$movie['id']}/credits";
  $query_params = array(
   'api_key' => $this->api_key,
  );

  $url      = add_query_arg($query_params, $endpoint);
  $response = wp_remote_get($url);

  if (is_wp_error($response)) {
   return $movie_cast;
  }

  $data = json_decode(wp_remote_retrieve_body($response), true);

  $movie_cast = isset($data['cast']) ? $data['cast'] : array();

  return $movie_cast;
 }

}
