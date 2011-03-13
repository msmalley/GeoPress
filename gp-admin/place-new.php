<?php
gp_title( __( 'Create New Place &lt; GeoPress' ) );
gp_breadcrumb( array(
	__('Create New Map'),
) );
gp_admin_header();
?>

<h2 class="light-title"><?php _e( 'Create New Place' ); ?></h2>

<div class="general-wrapper">

    <form action="" method="post">
    <?php gp_tmpl_load( 'place-form', get_defined_vars(), 'gp-admin/'); ?>
    </form>

</div>

<?php gp_tmpl_footer();