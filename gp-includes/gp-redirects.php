<?php

class GP_Router {

	var $api_prefix = 'api';
	
	function __construct( $urls = null ) {
		if ( is_null( $urls ) )
			$this->urls = $this->default_routes();
		else
			$this->urls = $urls;
	}
	
	/**
	* Returns the current request URI path, relative to
	* the application URI and without the query string
	*/
	function request_uri() {
		$subdir = rtrim( gp_url_path(), '/' );
		if ( preg_match( "@^$subdir(.*?)(\?.*)?$@", $_SERVER['REQUEST_URI'], $match ) )
			return urldecode( $match[1] );
		return false;
	}
	
	function request_method() {
		return gp_array_get( $_SERVER, 'REQUEST_METHOD', 'GET' );
	}
		
	function add( $re, $function, $method = 'get' ) {
		$this->urls["$method:$re"] = $function;
	}
	
	function default_routes() {
		$dir = '([^_/][^/]*)';
		$slug = '(.+?)';
		$maps = 'maps';
		$map = $maps.'/'.$slug;
		$places = 'places';
		$place = $places.'/'.$slug;
		$checkins = 'checkins';
		$checkin = $checkins.'/'.$slug;
		$id = '(\d+)';
		$locale = '('.implode('|', array_map( create_function( '$x', 'return $x->slug;' ), GP_Locales::locales() ) ).')';
		$set = "$map/$locale/$dir";
		// overall structure
		return apply_filters( 'routes', array(
											  
			'/' => array('GP_Route_Index', 'index'),
			
			'get:/login' => array('GP_Route_Login', 'login_get'),
			'post:/login' => array('GP_Route_Login', 'login_post'),
			'get:/logout' => array('GP_Route_Login', 'logout'),

			/* MAPS */
			"get:/$map/-edit" => array('GP_Route_Map', 'edit_get'),
			"post:/$map/-edit" => array('GP_Route_Map', 'edit_post'),
			"get:/$maps" => array('GP_Route_Map', 'index'),
			"get:/$maps/-new" => array('GP_Route_Map', 'new_get'),
			"post:/$maps/-new" => array('GP_Route_Map', 'new_post'),
			
			/* PLACES */
			"get:/$place/-edit" => array('GP_Route_Place', 'edit_get'),
			"post:/$place/-edit" => array('GP_Route_Place', 'edit_post'),
			"get:/$places/-new" => array('GP_Route_Place', 'new_get'),
			"post:/$places/-new" => array('GP_Route_Place', 'new_post'),
			"/$places" => array('GP_Route_Place', 'index'),
			"/$place" => array('GP_Route_Place', 'single'),
			
			/* CHECKINS */
			"get:/$checkin/-edit" => array('GP_Route_Checkin', 'edit_get'),
			"post:/$checkin/-edit" => array('GP_Route_Checkin', 'edit_post'),
			"get:/$checkins/-new" => array('GP_Route_Checkin', 'new_get'),
			"post:/$checkins/-new" => array('GP_Route_Checkin', 'new_post'),
			"/$checkins" => array('GP_Route_Checkin', 'index'),
			"/$checkin" => array('GP_Route_Checkin', 'single'),

			// keep this one at the bottom of the map, because it will catch anything starting with map...
			"/$map" => array('GP_Route_Map', 'single'),

		) );
	}

	
	function route() {
		$real_request_uri = $this->request_uri();
		$api_request_uri = $real_request_uri;
		$request_method = strtolower( $this->request_method() );
		foreach( array( $api_request_uri, $real_request_uri ) as $request_uri ) {
			foreach( $this->urls as $re => $func ) {
				foreach (array('get', 'post', 'head', 'put', 'delete') as $http_method) {
					if ( gp_startswith( $re, $http_method.':' ) ) {
						
						if ( $http_method != $request_method ) continue;
						$re = substr( $re, strlen( $http_method . ':' ));
						break;
					}
				}
				if ( preg_match("@^$re$@", $request_uri, $matches ) ) {
					if ( is_array( $func ) ) {
						list( $class, $method ) = $func;
						$route = new $class;
						$route->last_method_called = $method;
						$route->class_name = $class;
						GP::$current_route = &$route;
						$route->before_request();
						$route->request_running = true;
						// make sure after_request() is called even if we $this->exit_() in the request
						register_shutdown_function( array( &$route, 'after_request') );
						call_user_func_array( array( $route, $method ), array_slice( $matches, 1 ) );
						$route->after_request();
						do_action( 'after_request', $class, $method );
						$route->request_running = false;
					} else {
						call_user_func_array( $func, array_slice( $matches, 1 ) );
					}
					return;
				}
			}
		}
		return gp_tmpl_404();
	}
}
