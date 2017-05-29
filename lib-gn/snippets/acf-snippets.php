<?php

//* Contrôle si Advanced Custom Field est actif sur le site
if ( ! function_exists( 'get_field' ) ) {
	// Notice dans le back-office au moment de la désactivation
	add_action('admin_notices','gn_warning_admin_missing_acf');
	function gn_warning_admin_missing_acf() {
	   $plugin_url = get_bloginfo('url') . '/wp-admin/plugins.php';
	   $output = '<div id="my-custom-warning" class="error fade">';
	   $output .= sprintf('<p><strong>Attention</strong>, ce site ne fonctionne pas sans l\'extension <strong>Advanced Custom Fields</strong>. Merci d\'activer cette <a href="%s">extension</a>.</p>', $plugin_url);
	   $output .= '</div>';
	   echo $output;
	 }
	// Notice dans le front qui masque tout le contenu et affiche le lien pour ce connecter
	add_action( 'template_redirect', 'gn_template_redirect_warning_missing_acf', 0 );
	function gn_template_redirect_warning_missing_acf() {
		wp_die( sprintf( 'Ce site ne fonctionne pas sans l\'extension <strong>Advanced Custom Fields</strong>. Merci de vous <a href="%s">connecter au site</a> pour l\'activer.', wp_login_url() ) );
	}
}


// Page d'options

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Options du thème',
		'menu_title'	=> 'Options',
		'menu_slug' 	=> 'theme-reglage-base',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Page d\'Accueil',
		'menu_title'	=> 'Page d\'Accueil',
		'parent_slug'	=> 'theme-reglage-base',
		'menu_slug' 	=> 'theme-page-accueil',
	));

}


// NEW HEADER WITH ACF
remove_action('genesis_after_header','minimum_site_tagline');
add_action('genesis_after_header','gn_site_tagline');
function gn_site_tagline() {
	printf( '<div %s>', genesis_attr( 'site-tagline' ) );
	genesis_structural_wrap( 'site-tagline' );

		printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
			echo '<p class="site-description" itemprop="descrition">' . get_field('gn_slogan_gauche','options') . '</p>';
		echo '</div>';

		printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
			echo '<section class="widget widget-text">' . get_field('gn_slogan_droite','options') . '</section>';
		echo '</div>';

	genesis_structural_wrap( 'site-tagline', 'close' );
	echo '</div>';
}

///////////////////////
// PERSONNALISATION
// BACK END
// http://www.advancedcustomfields.com/resources/actions/acfinputadmin_head/
///////////////////////
function my_head_input()
{
	?>
<style type="text/css">
/* Field Label */
.acf_postbox p.label {
	font-size: 12px;
	line-height: 1.4em;
	padding: 0;
	color: #7E7878;
	text-shadow: none;
}

.field_type-message {
	background-color: #fff;
}

.field_type-message ul  {
	margin-left: 17px;
	list-style:  disc;
	line-height: 1em;
}

.texte-bleu {
	color: #01A5BB;
}
.texte-rouge {
	color: #E70D0D;
}

</style>


	<?php
}

add_action('acf/input/admin_head', 'my_head_input');


///////////////////////
// META WORDCAMPEUR
//
///////////////////////

// META VIGNETTE
// Deux paramètre optionnels
// le premier sert pour le champ ACF
// le deuxième pour les gravatar
function gn_meta_wordcampeur_image($gn_photo_size = '', $gn_photo_pixels = ''){

	if ( get_field('gn_photo') ) :
		$attachment_id = get_field('gn_photo');
		$size = $gn_photo_size;
		$image = wp_get_attachment_image($attachment_id, $size);
		echo $image;
	elseif ( get_field('gn_photo_gravatar') ) :
		echo '<img src="http://www.gravatar.com/avatar/'. get_field('gn_photo_gravatar') . '?s=' . $gn_photo_pixels . '" width="' . $gn_photo_pixels . '" height="' . $gn_photo_pixels . '" class="attachment-wordcampeur" />';
	else :
		echo '<img src="http://www.gravatar.com/avatar/?d=mm&s=' . $gn_photo_pixels . '" " width="' . $gn_photo_pixels . '" height="' . $gn_photo_pixels . '" class="attachment-wordcampeur" />';
	endif;
}

// META TEXTE

function gn_meta_wordcampeur_tableau() {
	return array(
		array(
			'url' => 'https://twitter.com/' . get_field('gn_twitter'),
			'texte' => '@' . get_field('gn_twitter')
		),
		array(
			'url' => get_field('gn_site'),
			'texte' => 'website'
		),
		array(
			'url' => get_field('gn_profil_wordpress'),
			'texte' => 'wordpress.org'
		)
	);
}

// $gn_test = gn_meta_wordcampeur_tableau;

function gn_meta_wordcampeur_texte(){

echo '<ul class="meta-wordcampeur list-unstyled">';
	$gn_meta = gn_meta_wordcampeur_tableau();

	foreach ( $gn_meta as $gn_meta ) :

		if ( ($gn_meta['url'] != '') && ($gn_meta['texte'] != '@') ) :
			echo '<li class='. $gn_meta['texte'] . '>';
				printf('<a href="%s" target="_blank" title="Ouverture du lien">%s</a>', esc_url($gn_meta['url']), $gn_meta['texte']);
			echo '</li>';
		endif;

	endforeach;

echo '</ul>';
}


// META BOUTONS

function gn_bouton_meta_more($gn_texte, $gn_class_bouton){
	echo '<a href="' .  get_permalink() .  '" class="' . $gn_class_bouton . ' btn btn-sm btn-warning">' . $gn_texte . '</a>';
}


///////////////////////
// MAQUEUR ARTICLES
// ARCHIVE CPT ET TAXO
// FUNCTION QUI REUNI TOUT
///////////////////////

function gn_content_cpt_wcevent() {
	echo '<a href="'. get_permalink() . '" class="gn-vign-wg-home">';
	gn_meta_wordcampeur_image('wordcampeur-s', 150);
	echo '</a>';
	echo '<div class="entry-content entry-widget-home">';
	gn_meta_wordcampeur_texte();
	gn_bouton_meta_more("En lire +", "gn-btn-home-widget");
	echo '</div>';
}

