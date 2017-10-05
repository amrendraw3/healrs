<?php
/**
 * @package WordPress
 * @subpackage Sweetdate
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Sweetdate 1.0
 */


/**
 * Sweetdate Child Theme Functions
 * Add extra code or replace existing functions
*/ 

// wp_enqueue_style( 'custom', get_template_directory_uri() . '/css/custom.css' );







// Load the theme stylesheets

	// Load all of the styles that need to appear on all pages
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/assets/style/custom.css' );
	// wp_enqueue_style( 'custom', plugins_url() . '/css/custom.css' );


remove_filter('the_content', 'wpautop');		

?>

