    <?php

class Options
{
    private $__movies_plugin_options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'movies_plugin_options_page'));
        add_action('admin_init', array($this, 'movies_plugin_options_page_init'));
    }

    public function movies_plugin_options_page()
    {
        add_options_page(
            'Movies Plugin Options', // page_title
            'Movies Plugin Options', // menu_title
            'manage_options', // capability
            'movies-plugin-options', // menu_slug
            array($this, 'movies_plugin_options_create_admin_page') // function
        );
    }

    public function movies_plugin_options_create_admin_page()
    {
        $this->movies_plugin_options = get_option('movies_plugin_options'); ?>

    <div class="wrap">
        <h2>Movies Plugin Options</h2>
        <p></p>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php
settings_fields('movies_plugin_options_option_group');
        do_settings_sections('movies-plugin-options-admin');
        submit_button();
        ?>
        </form>
    </div>
    <?php }

    /**
     * Register and add settings
     * @return void
     */
    public function movies_plugin_options_page_init()
    {
        register_setting(
            'movies_plugin_options_option_group', // option_group
            'movies_plugin_options', // option_name
            array($this, 'movies_plugin_options_sanitize') // sanitize_callback
        );

        add_settings_section(
            'movies_plugin_options_setting_section', // id
            'Settings', // title
            array($this, 'movies_plugin_options_section_info'), // callback
            'movies-plugin-options-admin' // page
        );

        add_settings_field(
            'api_key', // id
            'API Key', // title
            array($this, 'api_key_callback'), // callback
            'movies-plugin-options-admin', // page
            'movies_plugin_options_setting_section' // section
        );

        add_settings_field(
            'pages', // id
            'Pages for Movies', // title
            array($this, 'pages_movies_callback'), // callback
            'movies-plugin-options-admin', // page
            'movies_plugin_options_setting_section' // section
        );

        add_settings_field(
            'pages_actor', // id
            'Pages for Actor', // title
            array($this, 'pages_actor_callback'), // callback
            'movies-plugin-options-admin', // page
            'movies_plugin_options_setting_section' // section
        );

        add_settings_field(
            'archive_movies', // id
            'Archives for Movies', // title
            array($this, 'archive_movies_callback'), // callback
            'movies-plugin-options-admin', // page
            'movies_plugin_options_setting_section' // section
        );

        add_settings_field(
            'archive_actors', // id
            'Archives for Actors', // title
            array($this, 'archive_actors_callback'), // callback
            'movies-plugin-options-admin', // page
            'movies_plugin_options_setting_section' // section
        );
    }

    /**
     * Sanitize each setting field as needed
     * @param array $input Contains all settings fields as array keys
     * @return array
     */
    public function movies_plugin_options_sanitize($input)
    {
        $sanitary_values = array();
        if (isset($input['api_key'])) {
            $sanitary_values['api_key'] = sanitize_text_field($input['api_key']);
        }

        if (isset($input['pages'])) {
            $sanitary_values['pages'] = $input['pages'];
        }

        if (isset($input['pages_actor'])) {
            $sanitary_values['pages_actor'] = $input['pages_actor'];
        }

        if (isset($input['archive_movies'])) {
            $sanitary_values['archive_movies'] = $input['archive_movies'];
        }

        if (isset($input['archive_actors'])) {
            $sanitary_values['archive_actors'] = $input['archive_actors'];
        }

        return $sanitary_values;
    }

    public function archive_actors_callback()
    {
        $pages = get_pages();
        ?> <select name="movies_plugin_options[archive_actors]" id="pages">
        <option>Select a page</option>
        <?php
$selected_page = isset($this->movies_plugin_options['archive_actors']) ? esc_attr($this->movies_plugin_options['archive_actors']) : '';
        foreach ($pages as $page) {
            $option = '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }
    }

    public function archive_movies_callback()
    {
        $pages = get_pages();
        ?> <select name="movies_plugin_options[archive_movies]" id="pages">
        <option>Select a page</option>
        <?php
$selected_page = isset($this->movies_plugin_options['archive_movies']) ? esc_attr($this->movies_plugin_options['archive_movies']) : '';
        foreach ($pages as $page) {
            $option = '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }

        ?>
    </select> <?php
    }

    /**
      * Get the settings option array and print one of its values
      * @return void
      */
    public function api_key_callback()
    {
        printf(
            '<input class="regular-text" type="text" name="movies_plugin_options[api_key]" id="api_key" value="%s">',
            isset($this->movies_plugin_options['api_key']) ? esc_attr($this->movies_plugin_options['api_key']) : ''
        );
    }

    /**
     * Get all the pages and add them to the select dropdown
     *
     * @return void
     */
    public function pages_movies_callback()
    {
        $pages = get_pages();
        ?> <select name="movies_plugin_options[pages]" id="pages">
        <option>Select a page</option>
        <?php
$selected_page = isset($this->movies_plugin_options['pages']) ? esc_attr($this->movies_plugin_options['pages']) : '';
        foreach ($pages as $page) {
            $option = '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }

        ?>
    </select> <?php
    }

    /**
      * Get all the pages and add them to the select dropdown
      *
      * @return void
      */
    public function pages_actor_callback()
    {
        $pages = get_pages();
        ?> <select name="movies_plugin_options[pages_actor]" id="pages_actor">
        <option>Select a page</option>
        <?php
$selected_page = isset($this->movies_plugin_options['pages_actor']) ? esc_attr($this->movies_plugin_options['pages_actor']) : '';
        foreach ($pages as $page) {
            $option = '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>';
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }

        ?>
    </select> <?php
    }

    public function movies_plugin_options_section_info()
    {
        print 'Enter your settings below:';
    }
}
if (is_admin()) {
    $movies_plugin_options = new Options();
}

/*
 * Retrieve this value with:
 * $movies_plugin_options = get_option( 'movies_plugin_options' ); // Array of All Options
 * $api_key = $movies_plugin_options['api_key']; // API Key
 * $pages = $movies_plugin_options['pages']; // Pages
 */
