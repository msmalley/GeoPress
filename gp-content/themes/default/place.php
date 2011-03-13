<?php
gp_title( sprintf( __('%s &lt; GeoPress'), esc_html( $place->name ) ) );
gp_breadcrumb_place( $place );
wp_enqueue_script( 'common' );
wp_enqueue_style( 'geoforms' );
$edit_link = gp_link_place_edit_get( $place, '(edit)' );
$root = gp_url_path();
$checkin_url = $root.'checkins/-new?place_id='.$place->id;
$checkin_link = '<a href="'.$checkin_url.'" class="checkin button" id="checkin_'.$place->id.'">'.__('Checkin Here').'</a>';
$parity = gp_parity_factory();
gp_tmpl_header();
?>

<h2 class="light-title"><?php echo esc_html( $place->name ); ?> <?php echo $edit_link; ?> - <?php echo $checkin_link; ?></h2>

<div class="general-wrapper">

    <?php
    $map = GP::$map->by_id( $place->mapid );
    if ((( $map->private ) && (!GP::$user->logged_in())) || (( $place->private ) && (!GP::$user->logged_in()))) {
        if($map->private){
            echo '<p class="description">'.__('This place is on a private map; please log-in to view this place.').'</p>';
        }else{
            echo '<p class="description">'.__('This place is private; please log-in to view this place.').'</p>';
        }
    } else {    
        geopress_map($place,'place',450,13,'ROADMAP','',false,true);
        $place_description = $place->description;
        if(!empty($place_description)) {
            echo '<div class="floating_wrapper">'.$place_description.'</div>';
        }
    
    } ?>
    
</div>

<?php gp_tmpl_footer(); ?>