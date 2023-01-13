<?php
/*

@package movies-plugin
@author Gerardo Gonzalez
@license GPL-2.0-or-later

@wordpress-plugin

Plugin Name: Movies Plugin
Description: A plugin that shows information about the latest and most popular movies and actors, using data from the MovieDB API.
Author: Gerardo Gonzalez
Author URI: https://gerardo-gonzalez.dev/
Version: 1.0
Requires at least: 6.1
Requires PHP:      8.1
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Movies Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Movies Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Movies Plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function activate_movies_plugin()
{
}

define('MOVIES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MOVIES_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once plugin_dir_path(__FILE__) . 'includes/admin/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/options.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin/template-tags.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/MovieInfo.php';
require_once plugin_dir_path(__FILE__) . 'includes/classes/ActorInfo.php';
require plugin_dir_path(__FILE__) .'blocks/build/upcoming-movies/upcoming-movies.php';
require plugin_dir_path(__FILE__) .'blocks/build/most-popular-actor/most-popular-actor.php';

$movies_plugin_options = get_option('movies_plugin_options'); // Array of All Options
$api_key = $movies_plugin_options['api_key']; // API Key
define('API_KEY', $api_key);

$movie_info = new MovieInfo(API_KEY);
$actor_info = new ActorInfo(API_KEY);
$upcomingmovies = new UpcomingMovies();
$mostpopularactor = new MostPopularActor();

$movies_id = $movie_info->getMovies();
$movies_details = array();
foreach ($movies_id as $id) {
    array_push($movies_details, $movie_info->get_movie_details($id));
}
$upcomingmovies->setMovies($movies_details);
$upcomingmovies->init();

$actors_id = $actor_info->get_top_actors();
$actors_details = array();
foreach ($actors_id as $id) {
    array_push($actors_details, $actor_info->get_actor_details($id));
}
$mostpopularactor->setActor($actors_details);
$mostpopularactor->init();

register_activation_hook(__FILE__, 'activate_movies_plugin');
