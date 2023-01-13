<?php

class SearchForm
{
    /**
     * Constructor
     */
    public function init()
    {
        add_action('init', array($this, 'registrer'));
        add_action('wp_ajax_search_handler', array($this,'search_handler'));
        add_action('wp_ajax_nopriv_search_handler', array($this,'search_handler'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
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
            )
        );
    }

    public static function enqueueScripts()
    {
        wp_register_script('search_form_script', MOVIES_PLUGIN_URL . '/blocks/build/search-form/js/script.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('search_form_script');
        wp_localize_script('search_form_script', 'search_form_script', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('search_form_script_nonce'),
        ));
    }

    public function search_handler()
    {
        $search_term = $_GET['s'];

        $api_key = 'fb8b718901d5d692f48f10bd2e088dee';
        $endpoint = 'https://api.themoviedb.org/3/search/multi';
        $query_params = array(
            'api_key' => $api_key,
            'language' => 'en-US',
            'query' => $search_term,
            'page' => 1,
            'include_adult' => false
        );

        $url = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            wp_send_json_error(array( 'message' => 'An error occurred while searching.' ));
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        $actors = array();
        $movies = array();
        if (! empty($data['results'])) {
            foreach ($data['results'] as $result) {
                if ($result['media_type'] === 'person') {
                    array_push($actors, array(
                        'id' => $result['id'],
                        'name' => $result['name'],
                        'image' => isset($result['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $result['profile_path'] : 'https://via.placeholder.com/500x750',
                        'popularity' => $result['popularity'],
                        'url' => create_actor_link($result),
                        'btntext' => 'More info about this actor',
                         ));
                } elseif ($result['media_type'] === 'movie') {
                    array_push($movies, array(
                        'id' => $result['id'],
                        'name' => $result['title'],
                        'image' => isset($result['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $result['poster_path'] : 'https://via.placeholder.com/500x750',
                        'popularity' => $result['popularity'],
                        'url' => create_movie_link($result),
                        'btntext' => 'More info about this movie',
                         ));
                }
            }
        }

        $merged_array = $this-> sort_array_by_popularity(array_merge($actors, $movies));

        array_slice($merged_array, 0, 12);

        wp_send_json_success($merged_array);
    }

public function sort_array_by_popularity($data)
{
    usort($data, function ($a, $b) {
        return $b['popularity'] - $a['popularity'];
    });
    return $data;
}
}
