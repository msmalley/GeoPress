<?php
class GP_Place extends GP_Thing {
	
	var $table_basename = 'places';
	var $field_names = array( 'id', 'created', 'updated', 'mapid', 'latlng', 'name', 'description', 'slug', 'path', 'priavte', 'parent_place_id' );
	var $non_updatable_attributes = array( 'id' );


	function restrict_fields( $map ) {
		$map->name_should_not_be('empty');
	}
	
	// Additional queries

	function by_id( $id ) {
		return $this->one( "SELECT * FROM $this->table WHERE id = '%s'", trim( $id, '/' ) );
	}

	function by_path( $path ) {
		return $this->one( "SELECT * FROM $this->table WHERE path = '%s'", trim( $path, '/' ) );
	}
	
	function sub_maps() {
		return $this->many( "SELECT * FROM $this->table WHERE parent_map_id = %d ORDER BY active DESC, id ASC", $this->id );
	}
	
	function top_level() {
		return $this->many( "SELECT * FROM $this->table WHERE parent_map_id IS NULL ORDER BY name ASC" );
	}

	// Triggers
	
	function after_save() {
		// TODO: pass the update args to after/pre_save?		
		// TODO: only call it if the slug or parent map were changed
		return !is_null( $this->update_path() );
	}
	
	function after_create() {
		// TODO: pass some args to pre/after_create?
		if ( is_null( $this->update_path() ) ) return false;
	}

	// Field handling

	function normalize_fields( $args ) {
		$args = (array)$args;
		if ( isset( $args['parent_map_id'] ) ) {
			$args['parent_map_id'] = $this->force_false_to_null( $args['parent_map_id'] );
		}
		if ( isset( $args['slug'] ) && !$args['slug'] ) {
			$args['slug'] = gp_sanitize_for_url( $args['name'] );
		}
		if ( ( isset( $args['path']) && !$args['path'] ) || !isset( $args['path'] ) || is_null( $args['path'] )) {
			unset( $args['path'] );
		}
		if ( isset( $args['private'] ) ) {
			if ( 'on' == $args['private'] ) $args['private'] = 1;
			if ( !$args['private'] ) $args['private'] = 0;
		}
		return $args;
	}

	// Helpers
	
	/**
	 * Updates this place's and its chidlren's paths, according to its current slug.
	 */
	function update_path() {
		global $gpdb;
		$old_path = isset( $this->path )? $this->path : '';
		$parent_map = $this->get( $this->parent_map_id );
		if ( $parent_map )
			$path = gp_url_join( $parent_map->path, $this->slug.'-'.$this->id );
		elseif ( !$gpdb->last_error ) {
			$path = $this->slug.'-'.$this->id;
		}else{
			return null;
		}
		$this->path = $path;
		$res_self = $this->update( array( 'path' => $path ) );
		if ( is_null( $res_self ) ) return $res_self;
		// update children's paths, too
		if ( $old_path ) {
			$query = "UPDATE $this->table SET path = CONCAT(%s, SUBSTRING(path, %d)) WHERE path LIKE %s";
			return $this->query( $query, $path, strlen($old_path) + 1, like_escape( $old_path).'%' );
		} else {
			return $res_self;
		}
	}
	
	/**
	 * Regenrate the paths of all maps from its parents slugs
	 */
	function regenerate_paths( $parent_map_id = null ) {
		// TODO: do it with one query. Use the tree generation code from GP_Route_Main::_options_from_maps()
		if ( $parent_map_id ) {
			$parent_map = $this->get( $parent_map_id );
			$path = $parent_map->path;
		} else {
			$path = '';
			$parent_map_id = null;
		}
		$maps = $this->find( array( 'parent_map_id' => $parent_map_id ) );
		foreach( (array)$maps as $map ) {
			$map->update( array( 'path' => gp_url_join( $path, $map->slug.'-'.$map->id ) ) );
			$this->regenerate_paths( $map->id );
		}
	}
	
	function source_url( $file, $line ) {
		if ( $this->source_url_template() ) {
			return str_replace( array('%file%', '%line%'), array($file, $line), $this->source_url_template() );
		}
		return false;
	}
	
	function source_url_template() {
		if ( isset( $this->user_source_url_template ) )
			return $this->user_source_url_template;
		else {
			if ( $this->id && GP::$user->logged_in() && ($templates = GP::$user->current()->get_meta( 'source_url_templates' ))
					 && isset( $templates[$this->id] ) ) {
				$this->user_source_url_template = $templates[$this->id];
				return $this->user_source_url_template;
			} else {
				return $this->source_url_template;
			}
		}
	}
	
	/**
	 * Gives an array of map objects starting from the current map
	 * then its parent, its parent and up to the root
	 * 
	 * @todo Cache the results. Invalidation is tricky, because on each map update we need to invalidate the cache
	 * for all of its children.
	 * 
	 * @return array
	 */
	function path_to_root() {
		$path = array();
		if ( $this->parent_place_id ) {
			$parent_map = $this->get( $this->parent_place_id );
			$path = $parent_map->path_to_root();
		}
		return array_merge( array( &$this ), $path );
	}
	
	function set_difference_from( $other_map ) {
		$this_sets = (array)GP::$translation_set->by_map_id( $this->id );
		$other_sets = (array)GP::$translation_set->by_map_id( $other_map->id );
		$added = array();
		$removed = array();
		foreach( $other_sets as $other_set ) {
			$vars = array( 'locale' => $other_set->locale, 'slug' => $other_set->slug );
			if ( !gp_array_any( lambda('$set', '$set->locale == $locale && $set->slug == $slug', $vars ), $this_sets ) ) {
				$added[] = $other_set;
			}
		}
		foreach( $this_sets as $this_set ) {
			$vars = array( 'locale' => $this_set->locale, 'slug' => $this_set->slug );
			if ( !gp_array_any( lambda('$set', '$set->locale == $locale && $set->slug == $slug', $vars ), $other_sets ) ) {
				$removed[] = $this_set;
			}
		}
		return compact( 'added', 'removed' );
	}	
}
GP::$place = new GP_Place();