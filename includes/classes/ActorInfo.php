<?php

/**
 * Class ActorInfo
 *
 * @package MovieDB
 * @since 1.0.0
 * @version 1.0.0
 * @license GPL-2.0-or-later
 * @author Gerardo Gonzalez
 * @link https://gerardo-gonzalez.dev/
 *
 */
class ActorInfo
{
    private $__api_key  = 'fb8b718901d5d692f48f10bd2e088dee';
    private $__api_url  = 'https://api.themoviedb.org/3/person';
    private $__language = 'en-US';

    /**
     *
     * Constructor for the ActorInfo class.
     * @method __construct
     * @param {string} api_key - The API key to use when making requests to the MovieDB API.
     * @param {string} api_url - The base URL to use when making requests to the MovieDB API.
     *
     */
    public function __construct($api_key, $api_url = 'https://api.themoviedb.org/3/person')
    {
        $this->__api_key = $api_key;
        $this->__api_url = $api_url;
    }

    /**
     *
     * Retrieves a list of actors from the MovieDB API.
     * @method get_top_actors
     * @return {array} An array of actor IDs.
     *
     */
    public function get_top_actors()
    {
        $endpoint     = 'https://api.themoviedb.org/3/person/popular';
        $query_params = array(
          'api_key'  => $this->__api_key,
          'language' => $this->__language,
          'page'     => 1,
        );

        $url      = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array();
        }

        $data       = json_decode(wp_remote_retrieve_body($response), true);
        $top_actors = array();
        foreach (array_splice($data['results'], 0, 10) as $actor) {
            array_push($top_actors, $actor['id']);
        }

        return $top_actors;
    }

    /**
     *
     * Retrieves a list of movies from the MovieDB API.
     * @method get_actor_movies
     * @param {int} actor_id - The ID of the actor to retrieve movies for.
     * @return {array} An array of movie IDs.
     *
     */
    public function get_actor_details($actor_id)
    {
        $actor_details       = array();
        $actor_details['id'] = $actor_id;

        $endpoint     = $this->__api_url . "/$actor_id";
        $query_params = array(
          'api_key'  => $this->__api_key,
          'language' => $this->__language,
        );

        $url      = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $actor_details;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        $actor_details['birthday']       = isset($data['birthday']) ? $data['birthday'] : '';
        $actor_details['name']           = isset($data['name']) ? $data['name'] : '';
        $actor_details['biography']      = isset($data['biography']) ? $data['biography'] : '';
        $actor_details['popularity']     = isset($data['popularity']) ? $data['popularity'] : '';
        $actor_details['place_of_birth'] = isset($data['place_of_birth']) ? $data['place_of_birth'] : '';
        $actor_details['profile_path']   = isset($data['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $data['profile_path'] : '';

        return $actor_details;
    }

    /**
     *
     * Get actor popularity in HTML format
     *
     * @param  array  $actor      Actor data on JSON format
     * @param  string $stars_class CSS class for the stars
     *
     * @return string             HTML for the popularity
     *
     */
    public function get_actor_popularity_html($actor, $stars_class = 'stars')
    {
        if (!isset($actor['popularity'])) {
            return '<div>No popularity information available</div>';
        }
        $popularity = intval($actor['popularity'] / 10);
        $star_html  = '<div class="p-2 ' . $stars_class . ' popularity-' . $popularity . '">';
        for ($i = 0; $i < 5; $i++) {
            $star_html .= '<span class="star"></span>';
        }
        $star_html .= '</div>';
        return $star_html;
    }

    /**
     * Gets the HTML for the actor's homepage link.
     *
     * @param {Array} $actor - The array containing the actor details.
     * @return {string} The HTML for the actor's homepage link.
     *
     */
    public function get_actor_homepage_html($actor)
    {
        if (!isset($actor['homepage']) || empty($actor['homepage'])) {
            return '';
        }
        $homepage  = $actor['homepage'];
        $link_html = '<a href="' . $homepage . '" target="_blank">Visit actor\'s homepage</a>';
        return $link_html;
    }

    /**
     * Gets actor's movies.
     *
     * @param {Array} $actor_info - The array containing the actor details.
     * @return {Array} The array containing the actor's movies.
     *
     */
    public function get_actor_movies($actor_info)
    {
        if (!isset($actor_info['id'])) {
            return array();
        }

        $actor_id     = $actor_info['id'];
        $endpoint     = $this->__api_url . "/$actor_id/movie_credits";
        $query_params = array(
          'api_key'  => $this->__api_key,
          'language' => $this->__language,
        );

        $url      = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return array();
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        $movies = array();
        foreach ($data['cast'] as $movie) {
            $movies[] = array(
              'id'            => isset($movie['id']) ? $movie['id'] : '',
              'backdrop_path' => isset($movie['backdrop_path']) ? 'https://image.tmdb.org/t/p/w500' . $movie['backdrop_path'] : '',
              'character'     => isset($movie['character']) ? $movie['character'] : '',
              'title'         => isset($movie['title']) ? $movie['title'] : '',
              'release_date'  => isset($movie['release_date']) ? $movie['release_date'] : '',
            );
        }

        usort($movies, function ($a, $b) {
            return strtotime($b['release_date']) - strtotime($a['release_date']);
        });

        return $movies;
    }

    /**
     * Gets actor's images.
     *
     * @param {Array} $actor - The array containing the actor details.
     * @return {Array} The array containing the actor's images.
     *
     */
    public function get_actor_images($actor)
    {
        $actor_images = array();
        if (!isset($actor['id'])) {
            return $actor_images;
        }
        $actor_id     = $actor['id'];
        $endpoint     = $this->__api_url . "/$actor_id/images";
        $query_params = array(
          'api_key' => $this->__api_key,
        );

        $url      = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $actor_images;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (!isset($data['profiles'])) {
            return $actor_images;
        }

        $profiles = $data['profiles'];
        foreach ($profiles as $profile) {
            array_push($actor_images, 'https://image.tmdb.org/t/p/w500' . $profile['file_path']);
        }

        return $actor_images;
    }

/**
 * 
 * Retrieves a list of popular actors from the MovieDB API by page.
 * 
 * @method get_popular_actors
 * @param {int} page - The page number to retrieve actors for.
 * @return {array} An array of actors with their profile path and name.
 * 
 */
public function get_popular_actors()
{

$actors = array();

for ($i = 1; $i <= 5; $i++) {

$endpoint = 'https://api.themoviedb.org/3/person/popular';
$query_params = array(
'api_key' => $this->__api_key,
'language' => $this->__language,
'page' => $i,
);

$url      = add_query_arg($query_params, $endpoint);
$response = wp_remote_get($url);

if (is_wp_error($response)) {
    return array();
}

$data    = json_decode(wp_remote_retrieve_body($response), true);

if (!empty($data['results'])) {
    $results = array_slice($data['results'], 0, 9);
    foreach ($results as $result) {
        array_push($actors, array(
            'id'=> $result['id'],
            'profile_path' => isset($result['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $result['profile_path'] : 'https://via.placeholder.com/500x750',
            'name' => $result['name'],
        ));
    }
}
  
  }
return $actors;
}

}