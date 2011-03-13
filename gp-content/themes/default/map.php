<?php
gp_title( sprintf( __('%s &lt; GeoPress'), esc_html( $map->name ) ) );
gp_breadcrumb_map( $map );
wp_enqueue_script( 'common' );
wp_enqueue_style( 'geoforms' );
$edit_link = gp_link_map_edit_get( $map, ' [ edit ] ' );
$parity = gp_parity_factory();
gp_tmpl_header();
?>

<h2 class="light-title"><?php echo esc_html( $map->name ); ?> <?php echo $edit_link; ?></h2>

<div class="general-wrapper">

	<?php 
    if (( $map->private ) && (!GP::$user->logged_in())) {
        echo '<p class="description">'.__('This map is private; please log-in to view this map.').'</p>';
    } else {
        $limit = $map->numberofmarkers;
        if($map->display_type=='places'){
            $markers = gp_get_data($limit,'gp_places','mapid',$map->id);
        }else{
            $places = array();
            $checkins = gp_get_data($limit,'gp_checkins','mapid',$map->id);
            foreach($checkins as $checkin){
                $place = gp_get_data(1,'gp_places','id',$checkin['placeid']);
                $checkin_id = $checkin['id'];
                $places[$checkin_id]['id'] = $place[0]['id'];
                $places[$checkin_id]['checkin_time'] = $checkin['checkin_time'];
                $places[$checkin_id]['mapid'] = $checkin['mapid'];
                $places[$checkin_id]['latlng'] = $place[0]['latlng'];
                $places[$checkin_id]['name'] = gp_get_lingo_transport($checkin['mode'], $place[0]['name']);
                $places[$checkin_id]['description'] = $checkin['announcement'];
                $places[$checkin_id]['slug'] = $checkin['slug'];
                $places[$checkin_id]['path'] = $checkin['path'];
                $places[$checkin_id]['private'] = $place[0]['private'];
                $places[$checkin_id]['parent_place_id'] = $place[0]['parent_place_id'];
            }
            $markers = $places;
        }
        geopress_map($map,'map',450,13,'ROADMAP','',$markers);
        $map_description = $map->description;
        if(!empty($map_description)) {
            echo '<div class="floating_wrapper">'.$map_description.'</div>';
        }
    
    } ?>
</div>

<?php gp_tmpl_footer(); ?>