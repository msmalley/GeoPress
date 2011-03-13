<?php
class GP_Route_Map extends GP_Route_Main {
	
	function index() {
		$title = __('Maps');
		$maps = GP::$map->top_level();
		$this->tmpl( 'maps', get_defined_vars() );
	}
	
	function single( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		$title = sprintf( __('%s map '), esc_html( $map->name ) );
		$can_write = $this->can( 'write', 'map', $map->id );
		$this->tmpl( 'map', get_defined_vars() );
	}
	
	function personal_options_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$user = GP::$user->current();
		$source_url_templates = $user->get_meta( 'source_url_templates' );
		if ( !is_array( $source_url_templates ) ) $source_url_templates = array();
		$source_url_templates[$map->id] = gp_post( 'source-url-template' );
		if ( $user->set_meta( 'source_url_templates', $source_url_templates ) )
			$this->notices[] = 'Source URL template was successfully updated.';
		else
			$this->errors[] = 'Error in updating source URL template.';
		$this->redirect( gp_url_map( $map ) );
	}

	function import_originals_get( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$kind = 'originals';
		gp_tmpl_load( 'map-import', get_defined_vars() );
	}

	function import_originals_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;

		$format = gp_array_get( GP::$formats, gp_post( 'format', 'po' ), null );
		if ( !$format ) {
			$this->redirect_with_error( __('No such format.') );
			return;
		}


		if ( !is_uploaded_file( $_FILES['import-file']['tmp_name'] ) ) {
			// TODO: different errors for different upload conditions
			$this->redirect_with_error( __('Error uploading the file.') );
			return;
		}

		$translations = $format->read_originals_from_file( $_FILES['import-file']['tmp_name'] );
		if ( !$translations ) {
			$this->redirect_with_error( __('Couldn&#8217;t load translations from file!') );
		}
		
		list( $originals_added, $originals_existing ) = GP::$original->import_for_map( $map, $translations );
		$this->notices[] = sprintf(__("%s new strings were added, %s existing were updated."), $originals_added, $originals_existing );
				
		$this->redirect( gp_url_map( $map, 'import-originals' ) );
	}

	function edit_get( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		gp_tmpl_load( 'map-edit', get_defined_vars(), 'gp-admin/' );
	}
	
	function edit_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$updated_map = new GP_Map( gp_post( 'map' ) );
		if ( $this->invalid_and_redirect( $updated_map, gp_url_map( $map, '-edit' ) ) ) return;
		// TODO: add id check as a validation rule
		if ( $map->id == $updated_map->parent_map_id )
			$this->errors[] = __('The map cannot be parent of itself!');
		elseif ( $map->save( $updated_map ) )
			$this->notices[] = __('The map was saved.');
		else
			$this->errors[] = __('Error in saving map!');
		$map->reload();

		$this->redirect( gp_url_map( $map, '-edit' ) );
	}

	function delete_get( $map_path ) {
		// TODO: do not delete using a GET request but POST
		// TODO: decide what to do with child maps and translation sets
		// TODO: just deactivate, do not actually delete
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		if ( $map->delete() )
			$this->notices[] = __('The map was deleted.');
		else
			$this->errors[] = __('Error in deleting map!');
		$this->redirect( gp_url_map( '' ) );
	}

	
	function new_get() {
		$map = new GP_Map();
		$map->parent_map_id = gp_get( 'parent_map_id', null );
		if ( $this->cannot_and_redirect( 'write', 'map', $map->parent_map_id ) ) return;
		gp_tmpl_load( 'map-new', get_defined_vars(), 'gp-admin/' );
	}
	
	function new_post() {
		$post = gp_post( 'map' );
		$parent_map_id = gp_array_get( $post, 'parent_map_id', null );
		if ( $this->cannot_and_redirect( 'write', 'map', $parent_map_id ) ) return;
		$new_map = new GP_Map( $post );
		if ( $this->invalid_and_redirect( $new_map ) ) return;
		$map = GP::$map->create_and_select( $new_map );
		if ( !$map ) {
			$map = new GP_Map();
			$this->errors[] = __('Error in creating map!');
			gp_tmpl_load( 'map-new', get_defined_vars(), 'gp-admin/' );
		} else {
			$this->notices[] = __('The map was created!');
			$this->redirect( gp_url_map( $map, '-edit' ) );
		}
	}
	
	function permissions_get( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$path_to_root = array_slice( $map->path_to_root(), 1 );
		$permissions = GP::$validator_permission->by_map_id( $map->id );
		$cmp_fn = lambda( '$x, $y', 'strcmp($x->locale_slug, $y->locale_slug);' );
		usort( $permissions, $cmp_fn );
		$parent_permissions = array();
		foreach( $path_to_root as $parent_map ) {
			$this_parent_permissions = GP::$validator_permission->by_map_id( $parent_map->id );
			usort( $this_parent_permissions, $cmp_fn );
			foreach( $this_parent_permissions as $permission ) {
				$permission->map = $parent_map;
			}
			$parent_permissions = array_merge( $parent_permissions, (array)$this_parent_permissions );
		}
		// we can't join on users table
		foreach( array_merge( (array)$permissions, (array)$parent_permissions ) as $permission ) {
			$permission->user = GP::$user->get( $permission->user_id );
		}
		$this->tmpl( 'map-permissions', get_defined_vars() );
	}

	function permissions_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		if ( 'add-validator' == gp_post( 'action' ) ) {
			$user = GP::$user->by_login( gp_post( 'user_login' ) );
			if ( !$user ) {
				$this->redirect_with_error( __('User wasn&#8217;t found!'), gp_url_current() );
				return;
			}
			$new_permission = new GP_Validator_Permission( array(
				'user_id' => $user->id,
				'action' => 'approve',
				'map_id' => $map->id,
				'locale_slug' => gp_post( 'locale' ),
				'set_slug' => gp_post( 'set-slug' ),
			) );
			if ( $this->invalid_and_redirect( $new_permission, gp_url_current() ) ) return;
			$permission = GP::$validator_permission->create( $new_permission );
			$permission?
				$this->notices[] = __('Validator was added.') : $this->errors[] = __('Error in adding validator.');
		}
		$this->redirect( gp_url_current() );
	}
	
	function permissions_delete( $map_path, $permission_id ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$permission = GP::$permission->get( $permission_id );
		if ( $permission ) {
			if ( $permission->delete() ) {
				$this->notices[] = __('Permission was deleted.');
			} else {
				$this->errors[] = __('Error in deleting permission!');
			}
		} else {
			$this->errors[] = __('Permission wasn&#8217;t found!');
		}
		$this->redirect( gp_url_map( $map, array( '-permissions' ) ) );
	}

	function mass_create_sets_get( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$this->tmpl( 'map-mass-create-sets', get_defined_vars() );
	}

	function mass_create_sets_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$other_map = GP::$map->get( gp_post( 'map_id' ) );
		if ( !$other_map ) {
			$this->die_with_error( __('Map wasn&#8217;found') );
		}
		$changes = $map->set_difference_from( $other_map );
		$errors = 0;
		foreach( $changes['added'] as $to_add ) {
			if ( !GP::$translation_set->create( array('map_id' => $map->id, 'name' => $to_add->name, 'locale' => $to_add->locale, 'slug' => $to_add->slug) ) ) {
				$this->errors[] = __('Couldn&#8217;t add translation set named %s', esc_html( $to_add->name ) );
			}
		}
		foreach( $changes['removed'] as $to_remove ) {
			if ( !$to_remove->delete() ) {
				$this->errors[] = __('Couldn&#8217;t delete translation set named %s', esc_html( $to_remove->name ) );
			}
		}
		if ( !$this->errors ) $this->notices[] = __('Translation sets were added and removed successfully');
		$this->redirect( gp_url_map( $map ) );
	}

	function mass_create_sets_preview_post( $map_path ) {
		$map = GP::$map->by_path( $map_path );
		if ( !$map ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'map', $map->id ) ) return;
		$other_map = GP::$map->get( gp_post( 'map_id' ) );
		if ( !$other_map ) {
			$this->die_with_error( __('Map wasn&#8217;found') );
		}
		header('Content-Type: application/json');
		echo json_encode( $map->set_difference_from( $other_map ) );
	}	
}