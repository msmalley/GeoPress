<?php
/**
 * Finds and loads the config file and the bootstrapping code
 */

// Die if PHP is not new enough
if ( version_compare( PHP_VERSION, '5.2', '<' ) ) {
	die( sprintf( "Your server is running PHP version %s but GeoPress requires at least 5.2.\n", PHP_VERSION ) );
}

// Fix empty PHP_SELF
$PHP_SELF = $_SERVER['PHP_SELF'];
if ( empty($PHP_SELF) )
    $_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]);

/**
 * Define GP_PATH as this file's parent directory
 */
define( 'GP_PATH', dirname( __FILE__ ) . '/' );

define( 'GP_INC', 'gp-includes/' );

if ( defined( 'GP_CONFIG_FILE' ) && GP_CONFIG_FILE ) {
	require_once GP_CONFIG_FILE;
	require_once( GP_PATH . 'gp-settings.php' );
} elseif ( file_exists( GP_PATH . 'gp-config.php') ) {
	
	require_once( GP_PATH . 'gp-config.php');
	require_once( GP_PATH . 'gp-settings.php' );
	
} elseif ( file_exists( dirname( GP_PATH ) . '/gp-config.php') ) {
	
	require_once( dirname( GP_PATH ) . '/gp-config.php' );
	require_once( GP_PATH . 'gp-settings.php' );
	
} elseif ( !defined( 'GP_INSTALLING' ) || !GP_INSTALLING ) {

	$install_uri = preg_replace( '|/[^/]+?$|', '/', $_SERVER['PHP_SELF'] ) . 'install.php';
	header( 'Location: ' . $install_uri );
	die();

} else {
    /* ADD SOME STYLING TO THE ERROR PAGE */
    /* THIS PROVIDES A PLACE TO START ADDING INSTALL INSTRUCTIONS */
    /* CAN THEN ADD AS AN INCLUDE !!! :-) */
    ?>
    <style>
    body {
	background:#F8FDFF;
    }
    .install-wrapper {
        background:#FFF;
        border:1px solid #DDD;
        position:relative;
        width:80%;
        padding:5%;
        margin:25px 5%;
        text-align: center;
        font-weight: bold;
        background: #EFEFEF;
        background: -webkit-gradient(linear, 0 0, 0 100%, from(#FFF), to(#EEE));
        background: -moz-linear-gradient(top, #FFF, #EEE);
        -pie-background: linear-gradient(90deg, #EEE, #FFF);
        behavior: url(gp-admin/css/PIE.php);
        z-index:0;
    }
    </style>
    <?php
    // HOW TO HAVE THIS TRANSLATABLE IF NO GP SETTINGS ...?
    echo '<div class="install-wrapper">';
    echo 'gp-config.php does not yet exist!<br /><br />( please make a copy of gp-config-sample.php and rename it to gp-config.php after making changes to the database username, etc )';
    echo '</div>';
    //-> originally just this: die("gp-config.php doesn't exist! Please create one on top of gp-config-sample.php");
    die();
}