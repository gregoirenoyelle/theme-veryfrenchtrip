<?php

// Image une après le titre
add_action( 'genesis_entry_content', 'vft_image_avant_contenu', 5 );
function vft_image_avant_contenu() {
	// Si pas d'image une on s'arrête
	if ( ! has_post_thumbnail() ) return;
	// Affichag image une
	the_post_thumbnail( 'full');
}

// Ajout de la vidéo après le contenu
add_action( 'genesis_entry_content', 'vft_video_apres_contenu', 12 );
function vft_video_apres_contenu() {
	$video = get_field('url_de_la_video');
	$oembed = wp_oembed_get( $video, array('width' => 800) );
	if ( ! $video ) return;

	echo '<h3>(re) Voir!</h3>';
	echo $oembed;
}

genesis();