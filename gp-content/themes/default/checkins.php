<?php
gp_title( __('Checkins &lt; GeoPress') );
gp_breadcrumb(array( __('Checkins') ));
wp_enqueue_style( 'geoforms' );
gp_tmpl_header();
?>

<h2 class="light-title"><?php _e('Check-Ins'); ?></h2>

<div class="general-wrapper">
    
    <ul>
    <?php foreach($checkins as $checkin): ?>
    <?php
        $placeid = $checkin->placeid;
        $place = GP::$place->by_id($placeid);
        $placename = $place->name;
	$checkin_title = gp_get_lingo_transport($checkin->mode, $placename);
	?>
        <li class="floating_wrapper">
        	<div class="map-meta">
                <h3><?php gp_link_checkin( $checkin, esc_html( $checkin_title ) ); ?></h3>
                <span class="description"><?php echo gp_html_excerpt($checkin->announcement, 255); ?></span>
            </div>
            <div class="map-actions">
		<?php gp_link_checkin_edit( $checkin, null, array('class' => 'bubble') ); ?>
                <?php gp_link_checkin( $checkin, 'VIEW', array('class' => 'bubble action view') ); ?>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php if ( GP::$user->current()->can( 'write', 'place' ) ): ?>
        <p class="actionlist secondary"><?php gp_link( gp_url_checkin( '-new' ), __('Checkin-In') ); ?></p>
    <?php endif; ?>

</div>

<?php gp_tmpl_footer(); ?>