<?php

if( ! defined( 'ABSPATH' ) ){
    exit();
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option( 'pfr_delete_posts_per_year' );

?>