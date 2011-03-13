<?php
$placeid = $checkin->placeid;
$mode = $checkin->mode;
$place = GP::$place->by_id($placeid);
$placename = $place->name;
$checkin_title = gp_get_lingo_transport($mode, $placename);

gp_title( sprintf( __('%s &lt; GeoPress'), esc_html( $checkin_title ) ) );
gp_breadcrumb_checkin( $checkin, $placeid, $mode );
wp_enqueue_script( 'common' );
wp_enqueue_style( 'geoforms' );
$edit_link = gp_link_checkin_edit_get( $checkin, ' (edit)' );
$parity = gp_parity_factory();
gp_tmpl_header();
?>

<h2 class="light-title">
    <?php
    $placeid = $checkin->placeid;
    $place = GP::$place->by_id($placeid);
    $placename = $place->name;
    $place_url = gp_url_place($place->path);
    $checkin_title = gp_get_lingo_transport($checkin->mode, $placename);
    echo esc_html( $checkin_title );
    echo $edit_link;
    ?>
</h2>

<div class="general-wrapper">

    <?php
    $map = GP::$map->by_id( $place->mapid );
    if ((( $map->private ) && (!GP::$user->logged_in())) || (( $checkin->announcementtype=='whisper' ) && (!GP::$user->logged_in()))) {
        if($map->private){
            echo '<p class="description">'.__('This checkin is on a private map; please log-in to view this checkin.').'</p>';
        }else{
            echo '<p class="description">'.__('This checkin is private; please log-in to view this checkin.').'</p>';
        }
    } else {
        geopress_map($place,'checkin',450,13,'ROADMAP','',false,true,$place_url);
        $checkin_description = $checkin->announcement ;
        if(!empty($checkin_description)) {
            echo '<div class="floating_wrapper">'.$checkin_description.'</div>';
        }

    } ?>

</div>

<?php gp_tmpl_footer(); ?>