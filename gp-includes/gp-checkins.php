<?php

function gp_link_checkin_get( $checkin_or_path, $text, $attrs = array() ) {
	$attrs = array_merge( array( 'title' => 'Place: '.$text ), $attrs );
	return gp_link_get( gp_url_checkin( $checkin_or_path ), $text, $attrs );
}

function gp_link_checkin() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_checkin_get', $args);
}

function gp_link_checkin_edit_get( $checkin, $text = null, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'checkin', $checkin->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Edit' );
	return gp_link_get( gp_url_checkin( $checkin, '-edit' ), $text, gp_attrs_add_class( $attrs, 'action edit' ) );
}

function gp_link_checkin_edit() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_checkin_edit_get', $args);
}

function gp_link_checkin_delete_get( $checkin, $text = false, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'checkin', $checkin->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Delete' );
	return gp_link_get( gp_url_checkin( $checkin, '-delete' ), $text, gp_attrs_add_class( $attrs, 'action delete' ) );
}

function gp_link_checkin_delete() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_checkin_delete_get', $args);
}

function gp_url_checkin( $checkin_or_path = '', $path = '', $query = null ) {
	$checkin_path = is_object( $checkin_or_path )? $checkin_or_path->path : $checkin_or_path;
	return gp_url( array( 'checkins', $checkin_path, $path ), $query );
}

function gp_checkin_links_from_root( $leaf_map, $placeid, $mode = 'teleport' ) {
	$links = array();
	$path_from_root = array_reverse( $leaf_map->path_to_root() );
	$links[] = empty( $path_from_root)? 'Checkins' : gp_link_get( gp_url( '/checkins' ), 'Checkins' );
	foreach( $path_from_root as $checkin ) {
                $place = GP::$place->by_id($placeid);
                $placename = $place->name;
                $checkin_title = gp_get_lingo_transport($mode, $placename);
		$links[] = gp_link_checkin_get( $checkin, esc_html( $checkin_title ) );
	}
	return $links;
}

function gp_breadcrumb_checkin( $checkin, $placeid, $mode = 'teleport' ) {
	return gp_breadcrumb( gp_checkin_links_from_root( $checkin, $placeid, $mode ) );
}

function gp_get_lingo_transport($checkin_mode, $checkin_location) {
	if($checkin_mode == 'teleport') {
		$checkin_title = 'I teleported to '.$checkin_location;
	}elseif($checkin_mode == 'drive') {
		$checkin_title = 'I drove to '.$checkin_location;
	}elseif($checkin_mode == 'ride') {
		$checkin_title = 'I rode to '.$checkin_location;
	}elseif($checkin_mode == 'walk') {
		$checkin_title = 'I walked to '.$checkin_location;
	}elseif($checkin_mode == 'run') {
		$checkin_title = 'I ran to '.$checkin_location;
	}elseif($checkin_mode == 'fly') {
		$checkin_title = 'I flew to '.$checkin_location;
	}else{
            if(!empty($checkin_location)){
		$checkin_title = 'I am at '.$checkin_location;
            }else{
                $checkin_title = 'I am nowhere';
            }
	}	
	return $checkin_title;
}
function gp_lingo_transport($checkin_mode, $checkin_location) {
	echo gp_get_lingo_transport($checkin_mode, $checkin_location);
}