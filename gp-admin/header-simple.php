<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<?php
wp_enqueue_style( 'base' );
wp_enqueue_style( 'less_framework' );
wp_enqueue_style( 'less_admin' );
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'less' );
?>
<title><?php echo gp_title(); ?></title>
<?php gp_head(); ?>
</head>

<body class="simple">