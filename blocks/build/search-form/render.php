<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

extract($attributes);

?>

<section <?php echo get_block_wrapper_attributes(['class' => 'row']); ?> data-block="search-form">
<form class="input-group my-4 d-flex justify-content-center" id="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <input class="form-control" type="text" name="s" id="search-input" placeholder="<?php esc_attr_e('Search for movie or actor', 'movies-plugin'); ?>">
    <button type="submit" id="search-submit" class="btn btn-primary"><?php esc_html_e('Find', 'movies-plugin'); ?></button>
</form>
<div class="message alert alert-info d-none my-16 text-center" role="alert">
    Searching...
<div class="spinner-border" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
</div>
</section>

<div class="modal fade" id="searchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="searchModalLabel">Your search results:</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row" id="search-results"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
    </div>
</div>