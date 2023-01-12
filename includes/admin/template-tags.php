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
 
    $link = add_query_arg('movie_id', $actor['id'], get_permalink($page_id));
    return $link;
   }
   return '';
  }