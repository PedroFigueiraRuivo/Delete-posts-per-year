<?php
// define vars
$option = get_option( 'pfr_delete_posts_per_year' );

$year = pfr__dppy_validate( $option[ 'label_year' ] );
$limit = pfr__dppy_validate( $option[ 'label_limit' ] );


if( $limit > 0 ){
    $pfr_limitToLoop = $limit;
}else if( $limit == 0 ){
    $pfr_limitToLoop = -1;
}else{
    $pfr_limitToLoop = 100;
}

// validate time
if($year == date( 'Y' ) || $year < date( 'Y' )){
    if( $year != '' && strlen( $year ) == 4 ){
        pfr__dppy_deletePosts_Start( $option, $pfr_limitToLoop );
    }
}


// Start -> Functions

// main function
function pfr__dppy_deletePosts_Start( $table_DB, $pfr_limitToLoop ){

    add_action( 'init', function() use ( $table_DB, $pfr_limitToLoop ) {
        
        // Define paramets to posts and delet the arr of result

        $pfr_posts = get_posts( [
            'numberposts'	=> $pfr_limitToLoop,
            'post_type'		=> 'post',
            'post_status'   => [ 'publish', 'draft' ],
            'date_query' => array(
                array(
                    'year'  => $table_DB[ 'label_year' ],
                ),
            ),
        ] );

        pfr__dppy_runDelete( $pfr_posts, $table_DB );
        
        // user check delet imgs?
        if( isset( $table_DB[ 'label_checked' ] ) && $table_DB[ 'label_checked' ] == "Yes" ){
            // Define paramets to posts and delet the arr of result
            $pfr_imgs = get_posts( [
                'numberposts'	=> $pfr_limitToLoop,
                'post_type'		=> 'attachment',
                'date_query' => array(
                    array(
                        'year'  => $table_DB[ 'label_year' ],
                    ),
                ),
            ] );
            pfr__dppy_runDelete( $pfr_imgs, $table_DB );

        }

        delete_option( 'pfr_delete_posts_per_year' );
    });
}


// Delete posts and archives
function pfr__dppy_runDelete( $pfr_archivesParamer, $DB ){
    foreach ( $pfr_archivesParamer as $archives ) {
            wp_delete_post( $archives->ID, true );
    }
}


// validation values to work
function pfr__dppy_validate( $arg ){
    $validValue = isset( $arg ) ? esc_attr( $arg ) : '';

    if( $validValue != '' ){
        return $validValue = preg_replace("/[^0-9]/", "", $validValue);
    }

    return 100;
}

// End -> Functions
?>