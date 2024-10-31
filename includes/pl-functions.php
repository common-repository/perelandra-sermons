<?php

/**
 * Check to see if page has a shortcode
 * @param  string  $shortcode
 * @return boolean
 */
function pl_has_shortcode( $shortcode = '' ) {

    global $post;
    $post_obj = get_post( $post->ID );
    $found = false;

    if ( ! $shortcode )
        return $found;

    if ( stripos( $post_obj->post_content, '[' . $shortcode ) !== false )
        $found = true;

    return $found;

}

/**
 * Define Constant
 */
function pl_define_constant( $name, $value ) {

    if ( ! defined( $name ) ) {

        define( $name, $value );

    }

}
