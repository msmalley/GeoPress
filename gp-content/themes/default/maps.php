<?php
gp_title( __('Maps &lt; GeoPress') );
gp_breadcrumb(array( __('Maps') ));
wp_enqueue_style( 'geoforms' );
gp_tmpl_header();
?>

<h2 class="light-title"><?php _e('Maps'); ?></h2>

<div class="general-wrapper">

    <ul>
    <?php foreach($maps as $map): ?>
        <li class="floating_wrapper">
        	<div class="map-meta">
                <h3><?php gp_link_map( $map, esc_html( $map->name ) ); ?></h3>
                <span class="description"><?php echo gp_html_excerpt($map->description, 255); ?></span>
            </div>
            <div class="map-actions">
				<?php gp_link_map_edit( $map, null, array('class' => 'bubble') ); ?>
                <?php gp_link_map( $map, 'VIEW', array('class' => 'bubble') ); ?>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
    <?php if ( GP::$user->current()->can( 'write', 'map' ) ): ?>
        <p class="actionlist secondary"><?php gp_link( gp_url_map( '-new' ), __('Create a New Map') ); ?></p>
    <?php endif; ?>

</div>

<?php gp_tmpl_footer(); ?>