<?php
class GP {
	// Core GP Models
	static $map;
	static $user;
	static $checkin;
	static $place;
	static $permission;
	static $validator_permission;
	// MISC Models
	static $router;
	static $redirect_notices = array();
	static $translation_warnings;
	static $builtin_translation_warnings;
	static $current_route = null;
	static $formats;
	// Dedicated Plugin Array
	static $vars = array();
	// Plugin Model
	static $plugins;
}
GP::$plugins = new stdClass();