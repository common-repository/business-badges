<?php
/*
Author: Qualisure
Author URI: http://qualisure.es/
*/

class BB_styles {
    function BB_add_scripts () {

        wp_register_style('BB_style', plugin_dir_path( __FILE__ ) . '/css/businessbadges.css' );

        wp_enqueue_style( 'BB_style' );


    }
}