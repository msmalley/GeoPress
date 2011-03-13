<?php
/**
 * Defines default styles and scripts
 */

function gp_styles_default( &$styles ) {
	$styles->base_url = gp_url_base( 'gp-admin/css/' );
    $styles->default_version = gp_get_option( 'version' );
	
	// TODO: get text direction for current locale
    //$styles->text_direction = 'rtl' == get_bloginfo( 'text_direction' ) ? 'rtl' : 'ltr';
	$styles->text_direction = 'ltr';
	
	$styles->add( 'base', 'style.css', array(), '20100812' );
	$styles->add( 'geoforms', 'geoforms.css', array(), '1.0' );
	$styles->add( 'less_framework', 'framework.less', array(), '1.0' );
	$styles->add( 'less_admin', 'admin.less', array(), '1.0' );
	$styles->add( 'less_default', 'default.less', array(), '1.0' );
}

add_action( 'wp_default_styles', 'gp_styles_default' );

function gp_scripts_default( &$scripts ) {
	$scripts->base_url = gp_url_base( '' );
    $scripts->default_version = gp_get_option( 'version' );
	
	$scripts->add( 'jquery', 'gp-admin/js/jquery/jquery.js', array(), '1.4.2-min' );
	$scripts->add( 'load-map', 'gp-includes/js/load-map.js', array(), '1.0' );
        $scripts->add( 'headjs', 'gp-includes/js/head.js', array(), '0.9' );
	$scripts->add( 'jquery-ui', 'gp-admin/js/jquery/jquery-ui.js', array('jquery'), '1.8-0' );
	
	$scripts->add( 'tinymce', 'gp-admin/js/tiny_mce/tiny_mce.js', array(), '1.0' );
	$scripts->add( 'less', 'gp-admin/js/less.js', array(), '1.0' );
	
	$scripts->add( 'jquery-ui-autocomplete', null, array('jquery-ui'), '1.8' );
	$scripts->add( 'jquery-ui-selectable', null, array('jquery-ui'), '1.8' );
	$scripts->add( 'jquery-ui-tabs', null, array('jquery-ui'), '1.8' );
}

add_action( 'wp_default_scripts', 'gp_scripts_default' );