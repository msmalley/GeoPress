<?php
/**
 * Landing point for GeoPress installation
 */

define('GP_INSTALLING', true);
require_once( 'gp-load.php' );
require_once( BACKPRESS_PATH . 'class.bp-sql-schema-parser.php' );
require_once( GP_PATH . GP_INC . 'gp-install.php' );
require_once( GP_PATH . GP_INC . 'gp-schema.php' );

$show_htaccess_instructions = true;

$username = $_GET['username'];
$password = $_GET['password'];

if((empty($username))||(empty($password))){
    ?>
    <style>
    body {
        background:#F8FDFF;
        font-family: Arial;
    }
    .install-wrapper {
        background:#FFF;
        border:1px solid #DDD;
        position:relative;
        width:80%;
        padding:5%;
        margin:25px 5%;
        background: #EFEFEF;
        background: -webkit-gradient(linear, 0 0, 0 100%, from(#FFF), to(#EEE));
        background: -moz-linear-gradient(top, #FFF, #EEE);
        -pie-background: linear-gradient(90deg, #EEE, #FFF);
        behavior: url(gp-admin/css/PIE.php);
        z-index:0;
    }
    .install-wrapper label {
        clear:both;
        float:left;
        width:100%;
        padding:5px 0 10px;
        border-bottom:1px dotted #CCC;
    }
    .install-wrapper input#username,
    .install-wrapper input#password,
    .install-wrapper input#submit {
        clear:both;
        float:left;
        margin: 15px 0 5px;
        width: 100%;
        padding:8px;
    }
    .install-wrapper p {
        clear: both;
        padding:5px 0 15px;
    }
    .install-wrapper input#submit {
        width: auto;
    }
    </style>
    <div class="install-wrapper">
        <form id="install-form" type="post">
        <p style="font-weight:bold;"><?php _e('Please Pick a Username and Password for your GeoPress Install'); ?></p>
        <p>
            <label for="username"><?php _e('Username:'); ?></label>
            <input type="text" id="username" name="username" value="" autocomplete="off" />
        </p>
        <p>
            <label for="password"><?php _e('Password:'); ?></label>
            <input type="password" id="password" name="password" value="" autocomplete="off" />
        </p>
        <p><input type="submit" id="submit" name="submit" value="Submit" /></p>
        <p><?php _e('Please also remember that at this stage, for this first initial public release, the only way to change this later is via the database, so please remember to write it down somewhere safe or pick something unforgetable for now...'); ?></p>
        </form>
    </div>
    <?php
}else{

    if ( gp_get_option( 'gp_db_version' ) <= gp_get_option_from_db( 'gp_db_version' ) && !isset( $_GET['force'] ) ) {
            $success_message = __( 'You already have the latest version, no need to upgrade!' );
            $errors = array();
            $show_htaccess_instructions = false;
    } else {
        if ( gp_get( 'action', 'install' )  == 'upgrade' ) {
                $success_message = __( 'GeoPress was successully upgraded!' );
                $errors = gp_upgrade();
        } else {
                $success_message = __( 'GeoPress was successully installed!' );
                $errors = gp_install();
                    if ( !$errors ) {
                        gp_create_initial_contents($username,$password);
                    }
            }
    }

    // TODO: check if the .htaccess is in place or try to write it
    $show_htaccess_instructions = $show_htaccess_instructions && empty( $errors );
    $path = gp_add_slash( gp_url_path() );
    $action = gp_get( 'action', 'install' );
    gp_tmpl_load( 'install',  get_defined_vars(), 'gp-admin/' );

}