<?php
gp_title( sprintf( __( 'Edit Place %s &lt; GeoPress' ),  $place->name ) );
$view_link = gp_link_place_get( $place, ' [ view ] ' );
gp_breadcrumb_place( $place );
gp_admin_header();
?>

<h2 class="light-title"><?php echo wptexturize( sprintf( __('Edit place "%s"'), esc_html( $place->name ) ) ); ?> <?php echo $view_link; ?></h2>

<div class="general-wrapper">

    <form action="" method="post">
    <?php gp_tmpl_load( 'place-form', get_defined_vars(), 'gp-admin/'); ?>
    </form>

</div>

<?php gp_tmpl_footer();