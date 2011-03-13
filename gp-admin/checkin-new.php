<?php
gp_title( __( 'Create New Checkin &lt; GeoPress' ) );
gp_breadcrumb( array(
	__('Create New Checkin'),
) );
gp_admin_header();
?>

<h2 class="light-title"><?php _e( 'Create New Checkin' ); ?></h2>

<div class="general-wrapper">

    <form action="" method="post">
        <?php gp_tmpl_load( 'checkin-form', get_defined_vars(), 'gp-admin/'); ?>
    </form>

</div>

<?php gp_tmpl_footer();