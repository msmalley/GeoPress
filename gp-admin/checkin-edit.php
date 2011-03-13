<?php
$placeid = $checkin->placeid;
$mode = $checkin->mode;
$place = GP::$place->by_id($placeid);
$placename = $place->name;
$checkin_title = gp_get_lingo_transport($mode, $placename);
gp_title( sprintf( __( 'Edit Checkin: %s &lt; GeoPress' ),  $checkin_title ) );

$view_link = gp_link_checkin_get( $checkin, ' [ view ] ' );
gp_breadcrumb_checkin( $checkin, $placeid, $mode );
gp_admin_header();

?>

<h2 class="light-title"><?php echo wptexturize( sprintf( __('Edit checkin "%s"'), esc_html( $checkin_title ) ) ); ?> <?php echo $view_link; ?></h2>

<div class="general-wrapper">

    <form action="" method="post">
        <?php gp_tmpl_load( 'checkin-form', get_defined_vars(), 'gp-admin/'); ?>
    </form>

</div>

<?php gp_tmpl_footer();