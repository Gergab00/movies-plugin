<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

extract( $attributes );

?>
<section <?php echo get_block_wrapper_attributes(['class' => 'row']); ?> data-block="actors">
    <?php
$actors = $this->getActors();
print_r($actors);

foreach ($actors as $actor) {
?>
    <div class="col-md-4 pb-2">
        <div class="card rounded-4 text-bg-dark">
            <img src="<?php echo $actor['profile_path']; ?>" class="card-img-top rounded-4">
            <div class="card-body">
                <h5 class="card-title" style="height: 6rem;"><?php echo $actor['name']; ?></h5>
                <a href="<?php echo $this->create_actor_link($actor); ?>" class="btn btn-light">See more details</a>
            </div>
        </div>
    </div>
    <?php
}

?>

</section>