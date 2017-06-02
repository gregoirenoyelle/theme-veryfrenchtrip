<?php

// Image une après le titre
add_action( 'genesis_entry_content', 'vft_image_avant_contenu', 5 );
function vft_image_avant_contenu() {
	the_post_thumbnail( 'full');
}


genesis();