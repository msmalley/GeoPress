<?php
gp_title( __('Install &lt; GeoPress') );
gp_breadcrumb( array(
	'install' == $action? __('Install') : __('Upgrade'),
) );

gp_admin_header();
?>

<div class="install-wrapper">
<?php if ($errors): ?>
	<?php _e('There were some errors:'); ?>
	<pre>
		<?php echo implode("\n", $errors); ?>
	</pre>
<?php 
	else:
		echo $success_message;
	endif;
?>
</div>

<?php
// TODO: deny access to scripts folder
if ( $show_htaccess_instructions ): ?>
	<div class="install-wrapper">
		<?php _e('Please add the following to your <code>.htaccess</code> file:'); ?>
                <pre>
# BEGIN GeoPress
&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On
RewriteBase <?php echo $path . "\n"; ?>
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . <?php echo $path; ?>index.php [L]
&lt;/IfModule&gt;
# END GeoPress
                </pre>
		<?php _e('<strong>If you do not already have an .htaccess file in the root of your install, you will need to create one, then copy and paste the text from above.</strong>'); ?>
	</div>
<?php endif; ?>
	
<?php gp_admin_footer(); ?>