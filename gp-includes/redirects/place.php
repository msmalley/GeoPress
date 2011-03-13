<?php
class GP_Route_Place extends GP_Route_Main {
	
	function index() {
		$title = __('Places');
		$places = GP::$place->all();
		$this->tmpl( 'places', get_defined_vars() );
	}
	
	function single( $place_path ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		$title = sprintf( __('%s place '), esc_html( $place->name ) );
		$can_write = $this->can( 'write', 'place', $place->id );
		$this->tmpl( 'place', get_defined_vars() );
	}

	function edit_get( $place_path ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
		gp_tmpl_load( 'place-edit', get_defined_vars(), 'gp-admin/' );
	}
	
	function edit_post( $place_path ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
		$updated_place = new GP_Map( gp_post( 'place' ) );
		if ( $this->invalid_and_redirect( $updated_place, gp_url_place( $place, '-edit' ) ) ) return;
		// TODO: add id check as a validation rule
		if ( $place->id == $updated_place->parent_place_id )
			$this->errors[] = __('The place cannot be parent of itself!');
		elseif ( $place->save( $updated_place ) )
			$this->notices[] = __('The place was saved.');
		else
			$this->errors[] = __('Error in saving place!');
		$place->reload();

		$this->redirect( gp_url_place( $place, '-edit' ) );
	}

	function delete_get( $place_path ) {
		// TODO: do not delete using a GET request but POST
		// TODO: decide what to do with child places and translation sets
		// TODO: just deactivate, do not actually delete
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
		if ( $place->delete() )
			$this->notices[] = __('The place was deleted.');
		else
			$this->errors[] = __('Error in deleting place!');
		$this->redirect( gp_url_place( '' ) );
	}

	
	function new_get() {
		$place = new GP_Map();
		$place->parent_place_id = gp_get( 'parent_place_id', null );
		if ( $this->cannot_and_redirect( 'write', 'place', $place->parent_place_id ) ) return;
		gp_tmpl_load( 'place-new', get_defined_vars(), 'gp-admin/' );
	}
	
	function new_post() {
		$post = gp_post( 'place' );
		$parent_place_id = gp_array_get( $post, 'parent_place_id', null );
		if ( $this->cannot_and_redirect( 'write', 'place', $parent_place_id ) ) return;
		$new_place = new GP_Map( $post );
		if ( $this->invalid_and_redirect( $new_place ) ) return;
		$place = GP::$place->create_and_select( $new_place );
		if ( !$place ) {
			$place = new GP_Map();
			$this->errors[] = __('Error in creating place!');
			gp_tmpl_load( 'place-new', get_defined_vars(), 'gp-admin/' );
		} else {
			$this->notices[] = __('The place was created!');
			$this->redirect( gp_url_place( $place, '-edit' ) );
		}
	}
	
	function permissions_get( $place_path ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
		$path_to_root = array_slice( $place->path_to_root(), 1 );
		$permissions = GP::$validator_permission->by_place_id( $place->id );
		$cmp_fn = lambda( '$x, $y', 'strcmp($x->locale_slug, $y->locale_slug);' );
		usort( $permissions, $cmp_fn );
		$parent_permissions = array();
		foreach( $path_to_root as $parent_place ) {
			$this_parent_permissions = GP::$validator_permission->by_place_id( $parent_place->id );
			usort( $this_parent_permissions, $cmp_fn );
			foreach( $this_parent_permissions as $permission ) {
				$permission->place = $parent_place;
			}
			$parent_permissions = array_merge( $parent_permissions, (array)$this_parent_permissions );
		}
		// we can't join on users table
		foreach( array_merge( (array)$permissions, (array)$parent_permissions ) as $permission ) {
			$permission->user = GP::$user->get( $permission->user_id );
		}
		$this->tmpl( 'place-permissions', get_defined_vars() );
	}

	function permissions_post( $place_path ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
		if ( 'add-validator' == gp_post( 'action' ) ) {
			$user = GP::$user->by_login( gp_post( 'user_login' ) );
			if ( !$user ) {
				$this->redirect_with_error( __('User wasn&#8217;t found!'), gp_url_current() );
				return;
			}
			$new_permission = new GP_Validator_Permission( array(
				'user_id' => $user->id,
				'action' => 'approve',
				'place_id' => $place->id,
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
	
	function permissions_delete( $place_path, $permission_id ) {
		$place = GP::$place->by_path( $place_path );
		if ( !$place ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'place', $place->id ) ) return;
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
		$this->redirect( gp_url_place( $place, array( '-permissions' ) ) );
	}
	
}