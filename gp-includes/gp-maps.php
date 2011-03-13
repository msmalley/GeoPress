<?php

function gp_link_map_get( $map_or_path, $text, $attrs = array() ) {
	$attrs = array_merge( array( 'title' => 'Map: '.$text ), $attrs );
	return gp_link_get( gp_url_map( $map_or_path ), $text, gp_attrs_add_class( $attrs, 'action view' ) );
}

function gp_link_map() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_map_get', $args);
}

function gp_link_map_edit_get( $map, $text = null, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'map', $map->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Edit' );
	return gp_link_get( gp_url_map( $map, '-edit' ), $text, gp_attrs_add_class( $attrs, 'action edit' ) );
}

function gp_link_map_edit() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_map_edit_get', $args);
}

function gp_link_map_delete_get( $map, $text = false, $attrs = array() ) {
	if ( !GP::$user->current()->can( 'write', 'map', $map->id ) ) {
		return '';
	}
	$text = $text? $text : __( 'Delete' );
	return gp_link_get( gp_url_map( $map, '-delete' ), $text, gp_attrs_add_class( $attrs, 'action delete' ) );
}

function gp_link_map_delete() {
	$args = func_get_args();
	echo call_user_func_array('gp_link_map_delete_get', $args);
}

function gp_url_map( $map_or_path = '', $path = '', $query = null ) {
	$map_path = is_object( $map_or_path )? $map_or_path->path : $map_or_path;
	return gp_url( array( 'maps', $map_path, $path ), $query );
}

function gp_map_links_from_root( $leaf_map ) {
	$links = array();
	$path_from_root = array_reverse( $leaf_map->path_to_root() );
	$links[] = empty( $path_from_root)? 'Maps' : gp_link_get( gp_url( '/maps' ), 'Maps' );
	foreach( $path_from_root as $map ) {
		$links[] = gp_link_map_get( $map, esc_html( $map->name ) );
	}
	return $links;
}

function gp_breadcrumb_map( $map ) {
	return gp_breadcrumb( gp_map_links_from_root( $map ) );
}

function geopress_map(
	$map = array(),
	$map_id = 'default', 
	$default_map_height = 450, 
	$default_map_zoom = 13, 
	$default_map_type = 'ROADMAP', 
	$default_map_position = '',
        $marker_array = false,
        $show_map_marker = false,
        $marker_url = ''
	) {
	$map_height = $map->height;
	$map_position = $map->latlng;
	$map_type = $map->type;
	$map_zoom = $map->zoom;
	$show_map_array = false;
	if($show_map_array) {
		echo '<pre>';
		print_r($map);
		echo '</pre>';
	}
	if(empty($map_position)) {
		$map_position = $default_map_position;
	}
	if(empty($map_type)) {
		$map_type = $default_map_type;
	}
	if(empty($map_zoom)) {
		$map_zoom = $default_map_zoom;
	}
	if(empty($map_height)) {
		$map_height = $default_map_height;
	}
	?>
    
    	<style>
		/* THESE MAP OPTION OVERWRITE DEFAULT SETTINGS */
		#mapCanvas<?php echo $map_id; ?> {
			height:<?php echo $map_height; ?>px !important;
		}
		</style>
        
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		
        var geocoder<?php echo $map_id; ?>, map<?php echo $map_id; ?>, marker<?php echo $map_id; ?>;
		
		function geocodePosition<?php echo $map_id; ?>(pos) {
          geocoder<?php echo $map_id; ?>.geocode({
            latLng: pos
          }, function(responses) {
            if (responses && responses.length > 0) {
              updateMarkerAddress<?php echo $map_id; ?>(responses[0].formatted_address);
            } else {
              updateMarkerAddress<?php echo $map_id; ?>('Cannot determine address at this location.');
            }
		  });
        }
        
        function initialize<?php echo $map_id; ?>() {
			
			geocoder<?php echo $map_id; ?> = new google.maps.Geocoder();
          
			<?php
			if((empty($map_position)) || ($map_position == "0, 0") || ($map_position == "0")) {  ?>
				if(google.loader.ClientLocation){
					//alert('geocoder working');
					var latLng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
				}else{
					//alert('geocoder not working');
					var latLng = new google.maps.LatLng(0, 0);
				}
			<?php
			} else {  
			?>
				
				var latLng = new google.maps.LatLng(<?php echo $map_position; ?>);				
				
			<?php
			}
			?>
			
			/* NOT NEEDED YET
			var image<?php echo $map_id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_icon; ?>',
				new google.maps.Size(30, 30),
				new google.maps.Point(0, 0),
				new google.maps.Point(21, 22));				
	
			var shadow<?php echo $map_id; ?> = new google.maps.MarkerImage('<?php echo $this_marker_shadow; ?>',
				new google.maps.Size(40, 40),
				new google.maps.Point(0, 0),
				new google.maps.Point(26, 27));
    		*/
			
            map<?php echo $map_id; ?> = new google.maps.Map(document.getElementById('mapCanvas<?php echo $map_id; ?>'), {
                zoom: <?php echo $map_zoom ?>,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.<?php echo $map_type ?>
              });

              <?php if($show_map_marker===true){ ?>
            marker<?php echo $map_id; ?> = new google.maps.Marker({
			    <?php if(!empty($this_marker_icon)) { ?>
                    icon: image<?php echo $map_id; ?>,
				<?php } ?>
			    <?php if(!empty($this_marker_shadow)) { ?>
                    shadow: shadow<?php echo $map_id; ?>,
				<?php } ?>
                position: latLng,
                title: '<?php echo $map->name; ?>',
                map: map<?php echo $map_id; ?>
            });
            <?php

                if(!empty($marker_url)){
                    ?>

                         google.maps.event.addListener(marker<?php echo $map_id; ?>, "click", function(e) {
                            parent.location.href = '<?php echo $marker_url; ?>';
                        });

                    <?php
                }

            }elseif(is_array($marker_array)) {
                
                foreach($marker_array as $marker){
                    if((($marker['private']==1)&&(GP::$user->logged_in()))||($marker['private']==0)){
                        ?>
                        var latLng<?php echo $map_id; ?> = new google.maps.LatLng(<?php echo $marker['latlng']; ?>);
                        marker<?php echo $marker['id']; ?> = new google.maps.Marker({
                            position: latLng<?php echo $map_id; ?>,
                            title: '<?php echo $marker['name']; ?>',
                            map: map<?php echo $map_id; ?>
                        });
                        <?php if(empty($marker_url)){
                            if(!empty($marker['checkin_time'])){
                                $marker_url = gp_url_checkin($marker['path']);
                            }else{
                                $marker_url = gp_url_place($marker['path']);
                            }
                        } ?>
                        google.maps.event.addListener(marker<?php echo $marker['id']; ?>, "click", function(e) {
                            parent.location.href = '<?php echo $marker_url; ?>';
                        });
                        
                        <?php
                    }
                }
                ?>

            <?php } ?>
			
        }
		
        google.maps.event.addDomListener(window, 'load', initialize<?php echo $map_id; ?>);
		
    </script>
            
    <div class="gp_mapcanvas_wrapper">
        <div id="mapCanvas<?php echo $map_id; ?>" class="gp_mapcanvas_frontend"></div>
    </div>
    
    <?php
	
}