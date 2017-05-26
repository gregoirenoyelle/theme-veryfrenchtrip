<?php

// changer query
// pour CPT et archives
add_action('pre_get_posts','gn_change_order',1);
function gn_change_order( $query ){
	if ( is_post_type_archive('gnpost_wordcampeur') || is_tax(array('gntx_evenement','gntx_pays','gntx_spectateur')) ) {
		$query->set ('posts_per_page', -1);
		$query->set('orderby', 'title');
		$query->set('order','ASC');
	}
}


// query widget
// sur la HOME
function gn_query_widget_home($spectateur = 'spectateur-wceu-2013'){
$gn_query = new WP_Query (
	array(
		'post_type' => 'gnpost_wordcampeur',
		'gntx_spectateur' => $spectateur,
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1
	)
); // fin WP_Query


$i = 1;
while ( $gn_query->have_posts() ) : $gn_query->the_post();
	if ( $i > 4 ) {
		$i = 1;
	}
	gn_widget_open($i);
	echo '<a href="'. get_permalink() . '" class="gn-vign-wg-home">';
	gn_meta_wordcampeur_image('wordcampeur-s', 150);
	echo '</a>';
	echo '<div class="entry-content entry-widget-home">';
	gn_meta_wordcampeur_texte();
	gn_bouton_meta_more("En lire +", "gn-btn-home-widget");
	echo '</div>';
	gn_widget_close();
	$i++;
endwhile;
wp_reset_postdata();
}