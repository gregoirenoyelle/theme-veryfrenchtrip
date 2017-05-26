<?php


// pour enlever les info du post, après le titre de l'article
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

// pour enlever les métas du post en bas d'article
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// force le contenu complet
add_filter( 'genesis_pre_get_option_content_archive', 'gn_show_content_ideastream' );
function gn_show_content_ideastream() {
	return 'content';
}


genesis();