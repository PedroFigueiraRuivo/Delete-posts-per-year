<?php

$option = get_option( 'pfr_delete_posts_per_year' );


$value = isset( $option[ 'label_year' ] ) ? esc_attr( $option[ 'label_year' ] ) : '';

if( $value != '' ){
    $value = preg_replace("/[^0-9]/", "", $value);
}

if($value == date( 'Y' ) || $value < date( 'Y' )){
    $validate = true;
}

if( $value != '' && strlen( $value ) == 4 && $validate && $value >= 2004 ){

    function pfr__dppy_runDelete( $pfr_archivesParamer ){
        foreach ( $pfr_archivesParamer as $archives ) {
            if( get_the_date( 'Y', $archives->ID ) == get_option( 'pfr_delete_posts_per_year' )[ 'label_year' ] ){
                wp_delete_post( $archives->ID, true ); 
            }
        }
    }
    
    function pfr__dppy_delete_posts() {
        $pfr_limitToLoop = -1;
        $arrToPost = [
            'numberposts'	=> $pfr_limitToLoop,
            'post_type'		=> 'post',
            'post_status'   => [ 'publish', 'draft' ]
        ];

        $pfr_posts = get_posts( $arrToPost );
        pfr__dppy_runDelete( $pfr_posts );
        
        if( isset( get_option( 'pfr_delete_posts_per_year' )[ 'label_checked' ] ) && get_option( 'pfr_delete_posts_per_year' )[ 'label_checked' ] == "Yes" ){

            $pfr_imgs = get_posts( [
                'numberposts'	=> $pfr_limitToLoop,
                'post_type'     => 'attachment',
            ] );
            pfr__dppy_runDelete( $pfr_imgs );

        }

        delete_option( 'pfr_delete_posts_per_year' );
    }

    add_action( 'init', 'pfr__dppy_delete_posts' );

}

?>