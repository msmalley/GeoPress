<?php
/**
 * Guesses the final installed URI based on the location of the install script
 *
 * @return string The guessed URI
 */
function guess_uri()
{
	$schema = 'http://';
	if ( strtolower( gp_array_get( $_SERVER, 'HTTPS' ) ) == 'on' ) {
		$schema = 'https://';
	}
	$uri = preg_replace( '|/[^/]*$|i', '/', $schema . gp_array_get( $_SERVER, 'HTTP_HOST') . gp_array_get( $_SERVER, 'REQUEST_URI' ) );

	return rtrim( $uri, " \t\n\r\0\x0B/" ) . '/';
}

function gp_update_db_version() {
	gp_update_option( 'gp_db_version', GP_DB_VERSION );
}

function gp_upgrade_db() {
	global $gpdb;
	
	$alterations = BP_SQL_Schema_Parser::delta( $gpdb, gp_schema_get() );
	$messages = $alterations['messages'];
	$errors = $alterations['errors'];
	if ( $errors ) return $errors;
	
	gp_upgrade_data( gp_get_option_from_db( 'gp_db_version' ) );

	gp_update_db_version();
}

function gp_upgrade() {
    return gp_upgrade_db();
}

function gp_upgrade_data( $db_version ) {
	global $gpdb;
	// To-Do
}

function gp_install() {
    global $gpdb;
    
    $errors = gp_upgrade_db();
    
	if ( $errors ) return $errors;
	
	gp_update_option( 'uri', guess_uri() );
}

function gp_create_initial_contents($username='admin',$password='admin') {
	global $gpdb;
	$default_place_map = array(
		'name'			=> __('My Favourite Places'),
		'description' 		=> __('This is a map of my favourite places, installed by default with <a href="http://geopress.my">GeoPress</a>.'),
		'slug' 			=> __('favourite-places'),
		'path' 			=> __('favourite-places'),
                'display_type'          => 'places',
		'latlng' 		=> __('0, 0'), 
		'type' 			=> __('ROADMAP'), 
		'zoom' 			=> 13,
		'height'		=> 450,
		'numberofmarkers'	=> 10
	);
	$gpdb->insert($gpdb->maps, $default_place_map);
        $default_checkin_map = array(
		'name'			=> __('My Life'),
		'description' 		=> __('This is a map of my checkins, installed by default with <a href="http://geopress.my">GeoPress</a>.'),
		'slug' 			=> __('my-life'),
		'path' 			=> __('my-life'),
                'display_type'          => 'checkins',
		'latlng' 		=> __('0, 0'),
		'type' 			=> __('ROADMAP'),
		'zoom' 			=> 13,
		'height'		=> 450,
		'numberofmarkers'	=> 10
	);
	$gpdb->insert($gpdb->maps, $default_checkin_map);
	$default_place = array(
		'mapid'			=> 1,
		'latlng'		=> '0, 0', 
		'name' 			=> __('My IP Address'), 
		'description' 		=> __('This is the nearest access point for the IP address that was used to installed <a href="http://geopress.my">GeoPress</a>.'), 
		'slug' 			=> __('my-ip'), 
		'path' 			=> __('my-ip')
	);
	$gpdb->insert($gpdb->places, $default_place);
	$default_checkin = array(
		'mapid'			=> 2,
		'placeid'		=> 1,
		'latlng'		=> '0, 0',
		'mode' 			=> __('teleport'), 
		'announcement' 		=> __('I just installed <a href="http://geopress.my">GeoPress</a>...'),
		'announcementtype' 	=> __('shout'),
		'slug' 			=> __('my-first-teleport'),
		'path' 			=> __('my-first-teleport'),
	);
	$gpdb->insert($gpdb->checkins, $default_checkin);
	$admin = GP::$user->create( array( 'user_login' => $username, 'user_pass' => $password, 'user_email' => 'admin@geopress.my' ) );
	GP::$permission->create( array( 'user_id' => $admin->id, 'action' => 'admin' ) );
	//GP::$permission->create( array( 'user_id' => $admin->id, 'action' => 'write', 'object_type' => 'map', 'object_id' => 1 ) );
}