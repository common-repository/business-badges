<?php

/*
Plugin Name: Business Badges
Plugin URI: https://wordpress.org/plugins/business-badges/
Description: Business Badges allows you to display fully customizable social badges on your website, like Google Business badge, Google Plus badge or facebook badge. It creates a widget.
Version: 1.0
Author: Qualisure
Author URI: http://qualisure.es/
License: GPL2
prefix: BB_
*/
define( 'BB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define('BB_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
define('BB_PLUGIN_DIR_S', WP_PLUGIN_DIR . '/' . BB_PLUGIN_NAME);
define('BB_PLUGIN_DIR_ADMIN', BB_PLUGIN_DIR_S . '/admin');

/*

if ( is_admin() ) {
    require_once( BB_PLUGIN_DIR. DIRECTORY_SEPARATOR. 'admin' .DIRECTORY_SEPARATOR . 'BusinessBadgesAdmin.php' );
    add_action( 'init', array( 'BusinessBadgesAdmin', 'init' ) );
}
*/

include_once( BB_PLUGIN_DIR . 'BB_scripts.php' );
require_once( BB_PLUGIN_DIR . 'BB_styles.php' );


//require_once( BB_PLUGIN_DIR_ADMIN . '/BusinessBadgesAdmin.php' );
//add_action( 'init', array( 'BusinessBadgesAdmin', 'init' ) );
//add_action( 'admin_menu', array( 'BusinessBadgesAdmin', 'add_menu_page' ) );




//echo BB_PLUGIN_DIR_ADMIN . '/BusinessBadgesAdmin.php';

//width 180-450
//retrato|paisaje
//claro|oscuro
//foto
//eslogan


function load_external_jQuery() { // load external file
    wp_deregister_script( 'google_sdk' );
    wp_register_script('google_sdk', ("https://apis.google.com/js/platform.js"), false);
    wp_enqueue_script('google_sdk');

    wp_deregister_script( 'facebook_sdk' );
    wp_register_script('facebook_sdk', ("https://connect.facebook.net/es_LA/sdk.js"), false);
    wp_enqueue_script('facebook_sdk');

}
add_action('wp_enqueue_scripts', 'load_external_jQuery');

/**
 * Business badges widget control
 * @param array $args
 * @param array $params
 */
function BB_widget_control($args=array(), $params=array()) {
    //the form is submitted, save into database
    if (isset($_POST['submitted'])) {
        update_option('BB_widget_title', sanitize_text_field($_POST['widgettitle']));

        //GOOGLE +
        if (isset($_POST['gplusact'])){
            update_option('BB_widget_gplus_act', sanitize_text_field($_POST['gplusact']));
        }else{
            update_option('BB_widget_gplus_act', 'off');
        }
        update_option('BB_widget_urlgplus', sanitize_text_field($_POST['urlgplus']));


        //FACEBOOK
        if (isset($_POST['facebook_act'])){
            update_option('BB_widget_facebook_act', sanitize_text_field($_POST['facebook_act']));
        }else{
            update_option('BB_widget_facebook_act', 'off');
        }
        update_option('BB_widget_urlfacebook', sanitize_text_field($_POST['urlfacebook']));
        update_option('BB_widget_altfacebook', sanitize_text_field($_POST['altfacebook']));
        update_option('BB_widget_idfacebook', sanitize_text_field($_POST['idfacebook']));
        update_option('BB_widget_bidfacebook', sanitize_text_field($_POST['bidfacebook']));
        update_option('BB_widget_keyfacebook', sanitize_text_field($_POST['keyfacebook']));
    }

    ?>
    Title:<br />
    <input type="text" class="widefat" name="widgettitle" value="<?php echo stripslashes(get_option('BB_widget_title')); ?>" />
    <br /><br />


    Enable Google +:
    <input type="checkbox" class="widefat" name="gplusact" <?php if (stripslashes(get_option('BB_widget_gplus_act'))=='on'){echo "checked";} ?> />
    <br /><br />

    Google + URL:<br />
    <input type="text" class="widefat" name="urlgplus" value="<?php echo stripslashes(get_option('BB_widget_urlgplus')); ?>" />
    <br /><br />

    Enable facebook:
    <input type="checkbox" class="widefat" name="facebook_act" <?php if (get_option('BB_widget_facebook_act')=='on'){echo "checked";} ?> />
    <br /><br />

    Alt:<br />
    <input type="text" class="widefat" name="altfacebook" value="<?php echo stripslashes(get_option('BB_widget_altfacebook')); ?>" />
    <br /><br />

    facebook URL:<br />
    <input type="text" class="widefat" name="urlfacebook" value="<?php echo stripslashes(get_option('BB_widget_urlfacebook')); ?>" />
    <br /><br />

    facebook id:<br />
    <input type="text" class="widefat" name="idfacebook" value="<?php echo stripslashes(get_option('BB_widget_idfacebook')); ?>" />
    <br /><br />

    facebook bid:<br />
    <input type="text" class="widefat" name="bidfacebook" value="<?php echo stripslashes(get_option('BB_widget_bidfacebook')); ?>" />
    <br /><br />

    facebook key:<br />
    <input type="text" class="widefat" name="keyfacebook" value="<?php echo stripslashes(get_option('BB_widget_keyfacebook')); ?>" />
    <br /><br />

    <input type="hidden" name="submitted" value="1" />
<?php
}

/**
 * Add a widget control for BB_widget
 */
wp_register_widget_control(
    'BB_widget',		// id
    'Business Badges Widget',		// name
    'BB_widget_control'	// callback function
);

/**
 * BB_widget code
 */
function BB_widget() {

    echo get_option('BB_widget_title')."<br />";

    if (get_option('BB_widget_gplus_act')=='on'){
        $url = preg_replace("/^http:\/\//i", "", get_option('BB_widget_urlgplus'));
        $url = preg_replace("/^https:\/\//i", "", get_option('BB_widget_urlgplus'));

        echo "<script type='text/javascript' src='https://apis.google.com/js/platform.js' async defer>";
        echo "{lang: 'es'}";
        echo "</script>";

        echo "<!-- Insignia google+-->";
        echo "		<div class='g-page' data-width='180' data-href='//". stripslashes($url) ."' data-showtagline='false' data-rel='publisher'></div>";

        echo "<br />";

        //imágenes seguidores
        //echo "<div class='g-plus' data-action='followers' data-height='300' data-source='blogger:blog:followers' data-href='//". stripslashes($url) ."' data-width='320'></div>";
    }

    if ( get_option('BB_widget_facebook_act')=='on'){

        $url = get_option('BB_widget_urlfacebook');
        $id= get_option('BB_widget_idfacebook');
        $bid= get_option('BB_widget_bidfacebook');
        $key= get_option('BB_widget_keyfacebook');
        if (empty($id)){
            $id="https://www.facebook.com/badge.php?id=1";
        }else{
            $id="https://www.facebook.com/badge.php?id=".$id."&bid=".$bid."&key=".$key."";
        }

        echo "<!-- Facebook Badge START -->";
        echo "<div class='BBfacebookbadge'><a href='". $url ."' title='".get_option('BB_widget_altfacebook')."' target='_TOP'><img class='img' ".
            " src='".$id."' alt='".get_option('BB_widget_altfacebook')."' /></a></div><br />";
        echo "<!-- Facebook Badge END -->";
    }
}

/**
 * Add a badges widget.
 */

wp_register_sidebar_widget(
    'BB_widget',          // your unique widget id
    'Business Badges Widget',                 // widget name
    'BB_widget',  // callback function to display widget
    array(                      // options
        'description' => 'Shows business badges'
    )
);


/**
 * Control of like widget
 * @param array $args
 * @param array $params
 */
function BB_like_widget_control($args=array(), $params=array()) {
    //the form is submitted, save into database
    if (isset($_POST['submittedlike'])) {
        update_option('BB_like_widget_title', sanitize_text_field($_POST['widgetliketitle']));

        //GOOGLE +
        if (isset($_POST['gpluslikeact'])){
            update_option('BB_like_widget_gplus_act', sanitize_text_field($_POST['gpluslikeact']));
        }else{
            update_option('BB_like_widget_gplus_act', 'off');
        }

        //FACEBOOK
        if (isset($_POST['facebooklikeact'])){
            update_option('BB_like_widget_facebook_act', sanitize_text_field($_POST['facebooklikeact']));
        }else{
            update_option('BB_like_widget_facebook_act', 'off');
        }

    }

    ?>
    Title:<br />
    <input type="text" class="widefat" name="widgetliketitle" value="<?php echo stripslashes(get_option('BB_like_widget_title')); ?>" />
    <br /><br />

    Enable Google +:
    <input type="checkbox" class="widefat" name="gpluslikeact" <?php if (stripslashes(get_option('BB_like_widget_gplus_act'))=='on'){echo "checked";} ?> />
    <br /><br />

    Enable facebook:
    <input type="checkbox" class="widefat" name="facebooklikeact" <?php if (get_option('BB_like_widget_facebook_act')=='on'){echo "checked";} ?> />
    <br /><br />

    <input type="hidden" name="submittedlike" value="1" />
<?php
}


/**
 * like widget code
 */
function BB_like_widget() {

    echo get_option('BB_like_widget_title')."<br />";

    if (get_option('BB_like_widget_gplus_act')=='on'){
        echo "<!-- Botón +1. -->";
        echo "<div class='g-plusone' data-annotation='inline' data-width='180'></div>";

        //imágenes seguidores
        //echo "<div class='g-plus' data-action='followers' data-height='300' data-source='blogger:blog:followers' data-href='//". stripslashes($url) ."' data-width='320'></div>";
    }

    if ( get_option('BB_like_widget_facebook_act')=='on'){

        echo "<!-- facebook like -->";
        echo "<div id='fb-root'></div>";
        echo "<div class='fb-like'></div>";
    }

}

/**
 * Add a widget control for BB_like_widget
 */
wp_register_widget_control(
    'BB_like_widget',		// id
    'Business Badges like Widget',		// name
    'BB_like_widget_control'	// callback function
);

/**
 * Add like widget.
 */
wp_register_sidebar_widget(
    'BB_like_widget',          // your unique widget id
    'Business Badges like Widget',                 // widget name
    'BB_like_widget',  // callback function to display widget
    array(                      // options
        'description' => 'Shows facebook like, +1 google plus buttons'
    )
);






/**
 * Add a sidebar area.
 */
function BB_area_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar cb', 'Sidebar cb' ),
        'id'            => 'sidebar-cb',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widget cb">',
        'after_title'   => '</h2>',
    ) );
}


//add_action( 'widgets_init', 'BB_area_widgets_init' );


/**
 * Shows like buttons when a shortcode is entered. Usage [myshortcode facebook="ok" google="ok"]
 * @param $atts
 */
function BB_shortcode_handler($atts) {

    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );

     //"foo = {$a['foo']}";



        echo "<!-- Botón +1. -->";
        echo "<div class='g-plusone' data-annotation='inline' data-width='180'></div>";


        echo "<!-- facebook like -->";
        echo "<div id='fb-root'></div>";
        echo "<div class='fb-like'></div>";


}


add_shortcode( 'AddLikeButtons', 'BB_shortcode_handler' );


