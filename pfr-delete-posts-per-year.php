<?php
/*
 * Plugin Name:       PFR - Delete posts per year
 * Plugin URI:        https://github.com/PedroFigueiraRuivo/DeletePostsPerYear
 * Description:       Deleta posts de um ano inteiro
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pedro figueira - Ruivo
 * Author URI:        https://github.com/PedroFigueiraRuivo
 * Text Domain:       pfr-delete-posts-per-year
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !defined( 'PFR__DPPY_VERSION' ) ){
    define( 'PFR__DPPY_VERSION', '1.0' );
}

if( !defined( 'PFR__DPPY_NAME' ) ){
    define( 'PFR__DPPY_NAME', 'PFR - Delete posts per year' );
}

if( !defined( 'PFR__DPPY_SLUG' ) ){
    define( 'PFR__DPPY_SLUG', 'pfr-delete-posts-per-year' );
}

if( !defined( 'PFR__DPPY_SLUG_DB' ) ){
    define( 'PFR__DPPY_SLUG_DB', 'pfr_delete_posts_per_year' );
}

if( !defined( 'PFR__DPPY_BASENAME' ) ){
    define( 'PFR__DPPY_BASENAME', plugin_basename( __FILE__ ) );
}

if( !defined( 'PFR__DPPY_DIR' ) ){
    define( 'PFR__DPPY_DIR', plugin_dir_path( __FILE__ ) );
}


if( is_admin() ){
    require_once PFR__DPPY_DIR . 'includes/class-' . PFR__DPPY_SLUG . '-admin.php';

    $pfr__dppy_admin = new pfr__dppy_admin(
        PFR__DPPY_NAME,
        PFR__DPPY_BASENAME,
        PFR__DPPY_SLUG,
        PFR__DPPY_SLUG_DB,
        PFR__DPPY_VERSION
    );

    if( isset( get_option( 'pfr_delete_posts_per_year' )[ 'label_year' ] ) ){

        require_once PFR__DPPY_DIR . 'includes/' . PFR__DPPY_SLUG . '.php';

    }

}