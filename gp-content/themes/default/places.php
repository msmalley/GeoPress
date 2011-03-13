<?php
gp_title( __('Places &lt; GeoPress') );
gp_breadcrumb(array( __('Places') ));
wp_enqueue_style( 'geoforms' );
gp_tmpl_header();
$root = gp_url_path();
?>

<h2 class="light-title"><?php _e('Places'); ?></h2>

<div class="general-wrapper">

    <ul>
    <?php foreach($places as $place): ?>
        <li class="floating_wrapper">
        	<div class="map-meta">
                <h3><?php gp_link_place( $place, esc_html( $place->name ) ); ?></h3>
                <span class="description"><?php echo gp_html_excerpt($place->description, 255); ?></span>
            </div>
            <div class="map-actions">
		<?php gp_link_place_edit( $place, null, array('class' => 'bubble') ); ?>
                <?php gp_link_place( $place, 'VIEW', array('class' => 'bubble action view') ); ?>
                <?php if ( GP::$user->current()->can( 'write', 'place' ) ): ?>
                    <?php 
                    $checkin_url = $root.'checkins/-new?place_id='.$place->id;
                    $checkin_link = '<a href="'.$checkin_url.'" class="bubble edit checkin" id="checkin_'.$place->id.'">'.__('CHECKIN').'</a>';
                    echo $checkin_link;
                    //gp_link_place( $place, 'CHECKIN', array('class' => 'bubble action view') );
                    ?>
                <?php endif; ?>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php if ( GP::$user->current()->can( 'write', 'place' ) ): ?>
        <p class="actionlist secondary"><?php gp_link( gp_url_place( '-new' ), __('Create a New Place') ); ?></p>
    <?php endif; ?>

</div>

<?php gp_tmpl_footer(); ?>