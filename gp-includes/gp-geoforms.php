<?php

function geopress_geoform(
	$component = array(),
	$type_array = 'map',
	$map_id = 'default', 
	$default_map_height = 450, 
	$default_map_type = 'ROADMAP',
	$default_map_zoom = 13,
	$default_map_position = ''
	) {
        $map_height = $component->height;
        $map_position = $component->latlng;
        $map_type = $component->type;
        $map_zoom = $component->zoom;
        if(empty($map_type)){
            $map_type = $default_map_type;
        }if(empty($map_zoom)){
            $map_zoom = $default_map_zoom;
        }
	$show_map_array = false;
	if($show_map_array) {
		echo '<pre>';
		print_r($map);
		echo '</pre>';
	}
	if(empty($map_position)) {
		$map_position = $default_map_position;
	}
	if(empty($map_height)) {
		$map_height = $default_map_height;
	}
	?>
    
    	<style>
		/* THESE MAP OPTION OVERWRITE DEFAULT SETTINGS */
		#mapCanvas<?php echo $map_id; ?> {
			height:<?php echo $map_height; ?>px;
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
        
        function updateMarkerStatus<?php echo $map_id; ?>(str) {
          document.getElementById('markerStatus<?php echo $map_id; ?>').innerHTML = str;
        }
        
        function updateMarkerPosition<?php echo $map_id; ?>(latLng) {
          document.getElementById('<?php echo $type_array; ?>[latlng]').value = [
            latLng.lat(),
            latLng.lng()
          ].join(', ');
		}
        
        function updateMarkerAddress<?php echo $map_id; ?>(str) {
          document.getElementById('address<?php echo $map_id; ?>').value = str;
        }
        
        function initialize<?php echo $map_id; ?>() {
			
			geocoder<?php echo $map_id; ?> = new google.maps.Geocoder();
          
			<?php
			if((empty($map_position)) || ($map_position == "0, 0") || ($map_position == "0")) {  ?>
				if(google.loader.ClientLocation){
					var latLng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
                                        jQuery('#<?php echo $type_array; ?>[latlng]').val(''+google.loader.ClientLocation.latitude+','+google.loader.ClientLocation.longitude+'')
				}else{
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
            marker<?php echo $map_id; ?> = new google.maps.Marker({
			    <?php if(!empty($this_marker_icon)) { ?>
                    icon: image<?php echo $map_id; ?>,
				<?php } ?>
			    <?php if(!empty($this_marker_shadow)) { ?>
                    shadow: shadow<?php echo $map_id; ?>,
				<?php } ?>
                position: latLng,
                title: 'Drag Me',
                map: map<?php echo $map_id; ?>,
                draggable: true
            });
            // Update current position info.
            updateMarkerPosition<?php echo $map_id; ?>(latLng);
            geocodePosition<?php echo $map_id; ?>(latLng);
            
            // Add dragging event listeners.
            google.maps.event.addListener(marker<?php echo $map_id; ?>, 'dragstart', function() {
            updateMarkerAddress<?php echo $map_id; ?>('Dragging...');
			});
            
            google.maps.event.addListener(marker<?php echo $map_id; ?>, 'drag', function() {
            updateMarkerStatus<?php echo $map_id; ?>('Dragging...');
            updateMarkerPosition<?php echo $map_id; ?>(marker<?php echo $map_id; ?>.getPosition());
            });
            
            google.maps.event.addListener(marker<?php echo $map_id; ?>, 'dragend', function() {
            updateMarkerStatus<?php echo $map_id; ?>('Drag ended');
            geocodePosition<?php echo $map_id; ?>(marker<?php echo $map_id; ?>.getPosition());
            });
			
        }
    
        function getFormattedLocation<?php echo $map_id; ?>() {
          if (google.loader.ClientLocation.address.country_code == "US" &&
            google.loader.ClientLocation.address.region) {
            return google.loader.ClientLocation.address.city + ", " 
                + google.loader.ClientLocation.address.region.toUpperCase();
          } else {
            return  google.loader.ClientLocation.address.city + ", "
                + google.loader.ClientLocation.address.country_code;
          }
        }
                
        function codeAddress<?php echo $map_id; ?>() {
          var address = document.getElementById('search_address<?php echo $map_id; ?>').value;
          
            if (geocoder<?php echo $map_id; ?>) {
              geocoder<?php echo $map_id; ?>.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map<?php echo $map_id; ?>.setCenter(results[0].geometry.location);
                    marker<?php echo $map_id; ?>.setPosition(results[0].geometry.location);
					geocodePosition<?php echo $map_id; ?>(results[0].geometry.location);
					updateMarkerPosition<?php echo $map_id; ?>(results[0].geometry.location);
                } else {
                    
                    if (status == "ZERO_RESULTS") {
                        alert("Sorry, but the address specified cannot be found...");
                    } else if (status == "OVER_QUERY_LIMIT") {
                        alert("Sorry, but you have exceeded your query limit quota...");
                    } else if (status == "REQUEST_DENIED") {
                        alert("Sorry, but for some reason, Google denied your request...");
                    } else if (status == "INVALID_REQUEST") {
                        alert("Sorry, but for some reason, something seems to have gone wrong...");
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                        
                }
              });
            }
        }
        
        jQuery(document).ready(function(){
            jQuery('#search_address<?php echo $map_id; ?>').keypress(function(event){
                                                if(event.keyCode == 13){
                                                    codeAddress<?php echo $map_id; ?>();
                                                    return false;
                                                }
                                             });
        });
		
        google.maps.event.addDomListener(window, 'load', initialize<?php echo $map_id; ?>);
		
    </script>
    
   	<div id="mapFrame<?php echo $map_id; ?>" class="map_frame gp_mapframe">     
    	<div style="clear:both; width:100%; margin-bottom:15px;">  
        
            <div class="input_wrapper" style="width:75%; float:left"><input id="search_address<?php echo $map_id; ?>" name="search_address<?php echo $map_id; ?>" type="textbox" value=""></div>
            <input type="button" value="SEARCH" onclick="codeAddress<?php echo $map_id; ?>();" style="width:20%; text-align:center; float:left; margin-left:2%;">
                
			<div style="clear:both; width:100%; height:15px;"></div>
            
            <div class="gp_mapcanvas_wrapper">
	            <div id="mapCanvas<?php echo $map_id; ?>" class="gp_mapcanvas"></div>
            </div>
					
            <input type="hidden" id="<?php echo $type_array; ?>[latlng]" class="gp_locationvalue" name="<?php echo $type_array; ?>[latlng]" value="<?php echo esc_html( $component->latlng ); ?>" style="width:100%; float:left; margin:25px 0 10px;">
                    
		</div>
	</div>
	
	<?php /* THIS IS HARD-CODED HIDDEN FOR NOW JUST TO CLEAN UP UI FOR INITIAL ALPHA RELEASE */ ?>
    <div class="gp_meta_boxes other_stuff<?php echo $map_id; ?> gp_otherstuff" style="display:none;">
		<div id="infoPanel<?php echo $map_id; ?>" class="gp_infopanel">
			<div id="leftColumn<?php echo $map_id; ?>" class="gp_leftcolumn">
				<b><?php echo __('Closest address:', 'gp'); ?></b>
                
                <textarea id="address<?php echo $map_id; ?>" class="gp_address" name="geo_settings_closest_address" value="<?php if(!empty($meta['address'])) echo $meta['address']; ?>" class="closest_address"></textarea>
                
			</div>
			<div id="middleColumn<?php echo $map_id; ?>" class="gp_middlecolumn">&nbsp;</div>
			<div id="rightColumn<?php echo $map_id; ?>" class="gp_rightcolumn">
				<b><?php echo __('Marker status:', 'gp'); ?></b>
				<div id="markerStatus<?php echo $map_id; ?>"><i><?php echo __('Click and drag the marker.', 'gp'); ?></i></div>
			</div>
		</div>
	</div>
    <?php /* END OF HIDDEN SECTION */ ?>
    
    <?php
	
}

?>