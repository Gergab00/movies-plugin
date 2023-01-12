<?php


class ActorInfo {
    private $__api_key = 'fb8b718901d5d692f48f10bd2e088dee';
    private $__api_url = 'https://api.themoviedb.org/3/person';
    private $__language = 'en-US';

    public function __construct($api_key, $api_url = 'https://api.themoviedb.org/3/person') {
        $this->__api_key = $api_key;
        $this->__api_url = $api_url;
    }

    public function get_actor_details($actor_id) {
        $actor_details = array();
        $actor_details['id'] = $actor_id;

        $endpoint = $this->__api_url . "/$actor_id";
        $query_params = array(
            'api_key' => $this->__api_key,
            'language' => $this->__language
        );

        $url = add_query_arg($query_params, $endpoint);
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return $actor_details;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        $actor_details['birthday'] = isset($data['birthday']) ? $data['birthday'] : '';
        $actor_details['name'] = isset($data['name']) ? $data['name'] : '';
        $actor_details['biography'] = isset($data['biography']) ? $data['biography'] : '';
        $actor_details['popularity'] = isset($data['popularity']) ? $data['popularity'] : '';
        $actor_details['place_of_birth'] = isset($data['place_of_birth']) ? $data['place_of_birth'] : '';
        $actor_details['profile_path'] = isset($data['profile_path']) ? 'https://image.tmdb.org/t/p/w500' . $data['profile_path'] : '';

        return $actor_details;
    }
}
