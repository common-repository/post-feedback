<?php

/*
Plugin Name: Post Feedback
Plugin URI: http://pixelplay.me/
Description: Create categorical feedback assessment for your blog posts and allows your users to send their feedback.
Version: 1.1
Author: Arif Qodari
Author URI: http://about.me/arifqodari
Stable tag: 1.1
*/


/* Define constants */
define( 'PF_BASE_DIR', plugin_dir_url( __FILE__) );
define( 'PF_BASE_PATH', dirname( __FILE__) );
define( 'PF_WP_DIR', get_bloginfo('wpurl') );
define( 'PF_PLUGIN_NAME', 'Post Feedback' );

register_activation_hook( __FILE__, 'activation' );
function activation() {
  $pf_options = array();

  if( !get_option('pf_options') ){
    update_option('pf_options', $pf_options );
  }
}

include('inc/functions.php');
include('inc/settings.php');
include('inc/scripts.php');

?>
