<?php

$option = get_option( 'pfr_delete_posts_per_year' );


$value = isset( $option[ 'label_year' ] ) ? esc_attr( $option[ 'label_year' ] ) : '';

if( $value != '' ){
    $value = preg_replace("/[^0-9]/", "", $value);
}

if($value == date( 'Y' ) || $value < date( 'Y' )){
    $validate = true;
}

if( $value != '' && strlen( $value ) == 4 &&  $validate && $value >= 2004 ){
    
    function pfr__dppy_delete_posts() {

        $arrToPost = [
            'numberposts'		=> 10000,
            'post_type'			=> 'post',
            'post_status'       => [ 'publish', 'draft' ]
        ];

        $pfr_posts = get_posts( $arrToPost );

        foreach ( $pfr_posts as $pfr_thePost ) {
            if( get_the_date( 'Y', $pfr_thePost->ID ) == get_option( 'pfr_delete_posts_per_year' )[ 'label_year' ] ){
                wp_delete_post( $pfr_thePost->ID, true ); 
            }
        }
        
        $pfr_imgs = get_posts( [
            'numberposts'		=> 10000,
            'post_type' => 'attachment',
            'meta_value_num'	=> get_option( 'pfr_delete_posts_per_year' )[ 'label_year' ]
        ] );
            
            
        foreach ( $pfr_imgs as $pfr_theImg ) {
            if( get_the_date( 'Y', $pfr_theImg->ID ) == get_option( 'pfr_delete_posts_per_year' )[ 'label_year' ] ){
                wp_delete_attachment( $pfr_theImg->ID, true);
            }
        }

        delete_option( 'pfr_delete_posts_per_year' );

    }

    add_action( 'init', 'pfr__dppy_delete_posts' );

}

?>