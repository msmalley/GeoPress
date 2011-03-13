<?php

function gp_link_place_get( $place_or_path, $text, $attrs = array() ) {
	$attrs = array_merge( array( 'title' => 'Place: '.$text ), $attrs );
	return gp_link_get( gp_url_place( $place_or_path ), $text, $attrs );
}

function gp_link_place() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_place_get', $args);
}

function gp_link_place_edit_get( $place, $text = null, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'place', $place->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Edit' );
	return gp_link_get( gp_url_place( $place, '-edit' ), $text, gp_attrs_add_class( $attrs, 'action edit' ) );
}

function gp_link_place_edit() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_place_edit_get', $args);
}

function gp_link_place_delete_get( $place, $text = false, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'place', $place->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Delete' );
	return gp_link_get( gp_url_place( $place, '-delete' ), $text, gp_attrs_add_class( $attrs, 'action delete' ) );
}

function gp_link_place_delete() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_place_delete_get', $args);
}

function gp_url_place( $place_or_path = '', $path = '', $query = null ) {
	$place_path = is_object( $place_or_path )? $place_or_path->path : $place_or_path;
	return gp_url( array( 'places', $place_path, $path ), $query );
}

function gp_place_links_from_root( $leaf_map ) {
	$links = array();
	$path_from_root = array_reverse( $leaf_map->path_to_root() );
	$links[] = empty( $path_from_root)? 'Places' : gp_link_get( gp_url( '/places' ), 'Places' );
	foreach( $path_from_root as $place ) {
		$links[] = gp_link_place_get( $place, esc_html( $place->name ) );
	}
	return $links;
}

function gp_breadcrumb_place( $place ) {
	return gp_breadcrumb( gp_place_links_from_root( $place ) );
}