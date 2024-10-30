<?php
/*
Author: Qualisure
Author URI: http://qualisure.es/
*/

class BB_scripts {
 static function BB_add_scripts () {

        if (get_option('BB_widget_gplus_act')=='on'){
            wp_deregister_script( 'google_sdk' );
            $google_sdk = 'https://apis.google.com/js/platform.js?language=es';
            wp_register_script( 'google_sdk', $google_sdk);
            wp_enqueue_script( 'google_sdk');
        }
        if (get_option('BB_widget_facebook_act')=='on'){
            wp_deregister_script( 'facebook_sdk' );
            $facebook_sdk = 'https://connect.facebook.net/es_LA/sdk.js';
            wp_register_script( 'facebook_sdk', $facebook_sdk);
            wp_enqueue_script( 'facebook_sdk');
        }



    }



}