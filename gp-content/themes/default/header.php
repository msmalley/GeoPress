<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php
wp_enqueue_style( 'base' );
wp_enqueue_style( 'less_framework' );
wp_enqueue_style( 'less_default' );
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'less' );
wp_enqueue_script( 'headjs' );
?>
<title><?php echo gp_title(); ?></title>
<?php gp_head(); ?>
</head>

	<body>
        
        <div id="header">
        	<div class="general-wrapper">
            
	            <div id="gp-js-message"></div>

                <a class="logo" href="<?php echo gp_url( '/' ); ?>">
                    <img alt="<?php esc_attr(__('GeoPress logo')); ?>" src="<?php echo gp_url_img( 'geopress-logo.png' ); ?>" />
                </a>
                <?php echo gp_breadcrumb(); ?>
                <span id="hello">
                <?php 
                if (GP::$user->logged_in()):
                    $user = GP::$user->current();
                    
                    printf( __('Hi, %s.'), $user->user_login );
                    ?>
                    <a href="<?php echo gp_url('/logout')?>"><?php _e('Log out'); ?></a>
                <?php else: ?>
                    <strong><a href="<?php echo gp_url_login(); ?>"><?php _e('Log in'); ?></a></strong>
                <?php endif; ?>
                <?php do_action( 'after_hello' ); ?>
                </span>
                
                <span class="navigation">
                    <ul class="core-nav">
                    	<?php
						$mapclass = false; $placeclass = false; $checkinclass = false;
						$current_url = gp_url_current();
						$root_url = gp_url_base_root();
						$map_urls = $root_url.'maps';
						$place_urls = $root_url.'places';
						$checkin_urls = $root_url.'checkins';
						if(($current_url == $map_urls) || (strstr($current_url, $map_urls))) {
							$mapclass = 'current';
						}
						if(($current_url == $place_urls) || (strstr($current_url, $place_urls))) {
							$placeclass = 'current';
						}
						if(($current_url == $checkin_urls) || (strstr($current_url, $checkin_urls))) {
							$checkinclass = 'current';
						}
						?>
                        <li><a class="<?php echo $mapclass; ?>" href="<?php echo gp_url('/maps')?>">Maps</a></li>
                        <li><a class="<?php echo $placeclass; ?>" href="<?php echo gp_url('/places')?>">Places</a></li>
                        <li><a class="<?php echo $checkinclass; ?>" href="<?php echo gp_url('/checkins')?>">Check-Ins</a></li>
                    </ul>
                </span>
                
            </div>
        </div>
		
        <div class="general-wrapper">
        
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