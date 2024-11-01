<?php
get_header();
global $post;

?>
<div class="tuxedo tuxedo--spectacle__single">
    <?php echo do_shortcode('[tuxedo-spectacle spectacle_id="'. esc_attr($post->ID) .'"]'); ?>
</div>
<?php

get_footer();
