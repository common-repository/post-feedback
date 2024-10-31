<?php
add_action( 'admin_enqueue_scripts', 'pf_add_scripts' );
function pf_add_scripts( $hook ) {
  if( $hook == 'settings_page_pf_settings' ) {
    wp_enqueue_script( 'admin', PF_BASE_DIR.'js/admin.js', array( 'wp-color-picker', 'jquery' ), false, true );
  }

  if( $hook == 'edit.php' ) {
    wp_enqueue_script( 'googlejsapi', '//www.google.com/jsapi', array(), '1.0.0', false, true);
    wp_enqueue_script( 'posts', PF_BASE_DIR.'js/posts.js', array( 'jquery' ), false, true );
    wp_enqueue_style( 'pf-style', PF_BASE_DIR.'css/pf-style.css' );
  }
}

add_action( 'init', 'pf_add_front_scripts' );
function pf_add_front_scripts() {
  wp_register_script( "front", PF_BASE_DIR . 'js/front.js', array( 'jquery' ), false, true );
  wp_localize_script( 'front', 'frontAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );        
  wp_enqueue_script( 'front' );
  wp_enqueue_style( 'pf-style', PF_BASE_DIR.'css/pf-style.css' );
}
?>
