# Movie & Actor Info WordPress Plugin
This plugin allows you to display information about the latest and most popular movies and actors on your website, by receiving data from a third-party API.

## Features
-Displays upcoming and popular movies on your website
-Showcases actors and their details
-Includes a trailer for the movies
-Includes a cast for the movies

## Requirements
-A [TMDB API key](https://www.themoviedb.org/settings/api)
-Wordpress version 4.0 or higher

## Installation
1. Download the plugin from the WordPress repository or from GitHub
2. Go to your website's WordPress dashboard and navigate to Plugins > Add New
3. Click on the "Upload Plugin" button and select the plugin zip file you downloaded
4. Click on the "Install Now" button and wait for the plugin to be installed
5. Click on the "Activate" button to activate the plugin
6. Go to the plugin settings page, enter your TMDB API key and configure the plugin options.

## Setup
To start using all the tools that come with `Movies Plugin`  you need to install the necessary Node.js and Composer dependencies :

```sh
$ composer install
$ npm install
```

## Usage
1. To display a list of upcoming movies on your website, create a new page or post and add the shortcode **\[movie_info\]**
2. To display actor's details, create a new page or post and add the shortcode **\[actor_info\]**

## Customization
You can customize the output of the plugin by adding different classes and styles to the shortcodes.

## Support
If you encounter any issues or need assistance, please open an issue on the [GitHub repository](https://github.com/Gergab00/movie-plugin) or contact us via our [mail](contact@gerardo-gonzalez.dev).

## Contribution
We welcome any contributions to the plugin, please fork the repository and submit a pull request with your changes.

### Requirements

`Movie Plugin` requires the following dependencies:

- [Node.js](https://nodejs.org/)
- [Composer](https://getcomposer.org/)

### Available CLI commands

`Movies Plugin` comes packed with CLI commands tailored for WordPress theme development:
- `npm run build`: build the Gutenberg component files from the Blocks folder.
- `npm run start`: Create preview files of Gutenberg components from the Blocks folder.
- `npm run watch-run-css`: is an npm script used to monitor CSS file changes and automatically run a specific task when any changes are detected.
- `npm run css`: this script is responsible for executing a specific command related to the processing of CSS files.
- `composer lint:wpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- `composer lint:php` : checks all PHP files for syntax errors.
- `composer make-pot` : generates a .pot file in the `languages/` directory.
- `npm run compile:css` : compiles SASS files to css.
- `npm run compile:rtl` : generates an RTL stylesheet.
- `npm run watch` : watches all SASS files and recompiles them to css when they change.
- `npm run lint:scss` : checks all SASS files against [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).
- `npm run lint:js` : checks all JavaScript files against [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/).
- `npm run bundle` : generates a .zip archive for distribution, excluding development and system files.

## Changelog
-1.0: Initial release

## License
This plugin is licensed under the GPLv2 license.

## Acknowledgements
This plugin uses data from the [TMDB API](https://www.themoviedb.org/).

## Author
[Gerardo Gabriel Gonzalez Velazquez](https://gerardo-gonzalez.dev)

## Disclaimer
This plugin is provided "as is" with no guarantee or warranty. Use it at your own risk. We shall not be held liable for any damages resulting from the use of this plugin.

## Estructure
- movies-plugin
    - assets
        - css
        - js
    - includes
        - classes
            - MovieInfo.php
            - ActorInfo.php
        - admin
            - settings.php
            - options.php
        - movie-cpt.php
        - actor-cpt.php
        - widgets.php
    - templates
        - single-movie.php
        - single-actor.php
    - movies-plugin.php