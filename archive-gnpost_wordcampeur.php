<?php

// compteur jq
gn_cpt_counter_jq();

// FONCTION FILTRE GENESIS
gn_genesis_filter_cpt_taxo_base();
gn_genesis_filter_cpt_taxo_footer();

//* Ajouter le nouveau contenu
add_action('genesis_entry_content', 'gn_new_post_content');
function gn_new_post_content() {
	gn_content_cpt_wcevent();
}




//* Run the Genesis loop
genesis();