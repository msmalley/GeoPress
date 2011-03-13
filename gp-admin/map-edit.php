<?php
gp_title( sprintf( __( 'Edit Map %s &lt; GeoPress' ),  $map->name ) );
$view_link = gp_link_map_get( $map, ' [ view ] ' );
gp_breadcrumb_map( $map );
gp_admin_header();
?>

<h2 class="light-title"><?php echo wptexturize( sprintf( __('Edit map "%s"'), esc_html( $map->name ) ) ); ?> <?php echo $view_link; ?></h2>

<div class="general-wrapper">

    <form action="" method="post">
    <?php gp_tmpl_load( 'map-form', get_defined_vars(), 'gp-admin/'); ?>
    </form>

</div>

<?php gp_admin_footer();