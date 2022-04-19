<?php
/*
Plugin Name: Def Elementor
Description: Extended Elementor Pro
Author: Kabita Dhimal
*/

use DF\Modules\Posts\Skins\Skin_Classic;

/**
 * Added custom meta in Meta Data for Elementor Pro Post Widget
 */
add_action('plugins_loaded', function() {
	add_action( 'elementor/widget/posts/skins_init', function ( \Elementor\Widget_Base $widget ) {
		require __DIR__.'/modules/posts/skins/skin-classic.php';
		$widget->add_skin( new Skin_Classic( $widget ) );
	} );
});
