<?php

class MostPopularActor
{
    private $__actor;

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
            )
        );
    }

    public function setActor($actor)
    {
        $this->__actor = $actor;
    }

    public function getActors()
    {
        return $this->__actor;
    }

    public function create_actor_link($actor)
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
}
