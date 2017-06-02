<?php

// Image une après le titre
add_action( 'genesis_entry_content', 'vft_image_avant_contenu', 5 );
function vft_image_avant_contenu() {
	// Si pas d'image une on s'arrête
	if ( ! has_post_thumbnail() ) return;
	// Affichag image une
	the_post_thumbnail( 'full');
}


genesis();