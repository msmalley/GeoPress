<?php
gp_title( sprintf( __('%s &lt; GeoPress'), __('Login') ) );
gp_breadcrumb( array(
	__('Login'),
) );
gp_admin_header($args, true);
?>
    
    <div id="login-wrapper">
    	
        <div id="gp-login-logo"><a href="http://geopress.my" target="_blank">Powered by GeoPress.my</a></div>
    
	    <div id="gp-js-message"></div>

		<div class="clear after-h1"></div>
		<?php if (gp_notice('error')): ?>
			<div class="notes error">
				<?php echo gp_notice( 'error' ); //TODO: run kses on notices ?>
			</div>
		<?php endif; ?>
		<?php if (gp_notice()): ?>
			<div class="notes notice">
				<?php echo gp_notice(); ?>
			</div>
		<?php endif; ?>
		<?php do_action( 'after_notices' ); ?>
    
        <h2 class="light-title">Login</h2>
        <?php do_action( 'before_login_form' ); ?>
        <form action="<?php echo gp_url_ssl( gp_url_current() ); ?>" method="post" class="standard-form">
        <dl>
            <dt><label for="user_login"><?php _e('Username'); ?></label></dt>
            <dd class="input_wrapper ui-corner-all-5"><input type="text" value="" id="user_login" name="user_login" /></dd>
            
            <dt><label for="user_pass"><?php _e('Password'); ?></label></dt>
            <dd class="input_wrapper ui-corner-all-5"><input type="password" value="" id="user_pass" name="user_pass" /></dd>
        </dl>
        <p><input type="submit" name="submit" value="<?php _e('Login'); ?>" id="submit" class="right"></p>
        <input type="hidden" value="<?php echo esc_attr( gp_get( 'redirect_to' ) ); ?>" id="redirect_to" name="redirect_to" />
        </form>
        <?php do_action( 'after_login_form' ); ?>
    </div>

<script type="text/javascript" charset="utf-8">
	document.getElementById('user_login').focus();
</script>
<?php gp_admin_footer($args, true);
