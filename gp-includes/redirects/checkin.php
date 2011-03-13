<?php
class GP_Route_Checkin extends GP_Route_Main {
	
	function index() {
		$title = __('Checkins');
		$checkins = GP::$checkin->all();
		$this->tmpl( 'checkins', get_defined_vars() );
	}
	
	function single( $checkin_path ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		$title = sprintf( __('%s checkin '), esc_html( $checkin->announcement ) );
		$can_write = $this->can( 'write', 'checkin', $checkin->id );
		$this->tmpl( 'checkin', get_defined_vars() );
	}

	function edit_get( $checkin_path ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
		gp_tmpl_load( 'checkin-edit', get_defined_vars(), 'gp-admin/' );
	}
	
	function edit_post( $checkin_path ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
		$updated_checkin = new GP_Map( gp_post( 'checkin' ) );
		if ( $this->invalid_and_redirect( $updated_checkin, gp_url_checkin( $checkin, '-edit' ) ) ) return;
		// TODO: add id check as a validation rule
		if ( $checkin->id == $updated_checkin->parent_checkin_id )
			$this->errors[] = __('The checkin cannot be parent of itself!');
		elseif ( $checkin->save( $updated_checkin ) )
			$this->notices[] = __('The checkin was saved.');
		else
			$this->errors[] = __('Error in saving checkin!');
		$checkin->reload();

		$this->redirect( gp_url_checkin( $checkin, '-edit' ) );
	}

	function delete_get( $checkin_path ) {
		// TODO: do not delete using a GET request but POST
		// TODO: decide what to do with child checkins and translation sets
		// TODO: just deactivate, do not actually delete
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
		if ( $checkin->delete() )
			$this->notices[] = __('The checkin was deleted.');
		else
			$this->errors[] = __('Error in deleting checkin!');
		$this->redirect( gp_url_checkin( '' ) );
	}

	
	function new_get() {
		$checkin = new GP_Map();
		$checkin->parent_checkin_id = gp_get( 'parent_checkin_id', null );
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->parent_checkin_id ) ) return;
		gp_tmpl_load( 'checkin-new', get_defined_vars(), 'gp-admin/' );
	}
	
	function new_post() {
		$post = gp_post( 'checkin' );
		$parent_checkin_id = gp_array_get( $post, 'parent_checkin_id', null );
		if ( $this->cannot_and_redirect( 'write', 'checkin', $parent_checkin_id ) ) return;
		$new_checkin = new GP_Map( $post );
		if ( $this->invalid_and_redirect( $new_checkin ) ) return;
		$checkin = GP::$checkin->create_and_select( $new_checkin );
		if ( !$checkin ) {
			$checkin = new GP_Map();
			$this->errors[] = __('Error in creating checkin!');
			gp_tmpl_load( 'checkin-new', get_defined_vars(), 'gp-admin/' );
		} else {
			$this->notices[] = __('The checkin was created!');
			$this->redirect( gp_url_checkin( $checkin, '-edit' ) );
		}
	}
	
	function permissions_get( $checkin_path ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
		$path_to_root = array_slice( $checkin->path_to_root(), 1 );
		$permissions = GP::$validator_permission->by_checkin_id( $checkin->id );
		$cmp_fn = lambda( '$x, $y', 'strcmp($x->locale_slug, $y->locale_slug);' );
		usort( $permissions, $cmp_fn );
		$parent_permissions = array();
		foreach( $path_to_root as $parent_checkin ) {
			$this_parent_permissions = GP::$validator_permission->by_checkin_id( $parent_checkin->id );
			usort( $this_parent_permissions, $cmp_fn );
			foreach( $this_parent_permissions as $permission ) {
				$permission->checkin = $parent_checkin;
			}
			$parent_permissions = array_merge( $parent_permissions, (array)$this_parent_permissions );
		}
		// we can't join on users table
		foreach( array_merge( (array)$permissions, (array)$parent_permissions ) as $permission ) {
			$permission->user = GP::$user->get( $permission->user_id );
		}
		$this->tmpl( 'checkin-permissions', get_defined_vars() );
	}

	function permissions_post( $checkin_path ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
		if ( 'add-validator' == gp_post( 'action' ) ) {
			$user = GP::$user->by_login( gp_post( 'user_login' ) );
			if ( !$user ) {
				$this->redirect_with_error( __('User wasn&#8217;t found!'), gp_url_current() );
				return;
			}
			$new_permission = new GP_Validator_Permission( array(
				'user_id' => $user->id,
				'action' => 'approve',
				'checkin_id' => $checkin->id,
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
	
	function permissions_delete( $checkin_path, $permission_id ) {
		$checkin = GP::$checkin->by_path( $checkin_path );
		if ( !$checkin ) gp_tmpl_404();
		if ( $this->cannot_and_redirect( 'write', 'checkin', $checkin->id ) ) return;
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
		$this->redirect( gp_url_checkin( $checkin, array( '-permissions' ) ) );
	}
	
}