<?php
add_action( 'the_content', 'pf_content' );
function pf_content( $content ) {
  if ( is_single() ) {
    global $post;
    $nonce = wp_create_nonce( "pf_submit_feedback" );

    $link = admin_url( "admin.php?action=pf_submit_feedback&post_id=" . $post->ID . "&nonce=" . $nonce );
    $config = get_option( 'pf_options' );
    $pf_enable = $config["enable"];
    $pf_options = $config["labels"];
    $pf_n_options = $config["n_options"];
    $current_user = wp_get_current_user();
    $post_feedback_user = get_user_meta( $current_user->ID, "post_feedback_user", true );

    if ( ( is_user_logged_in() && $pf_enable == "1" ) && ( empty( $post_feedback_user ) || !isset( $post_feedback_user[$post->ID] ) ) ) {
      $added_content = "";
      $added_content .= "<div class='pf_content'>";
      $added_content .= "<form name='pf_form'>";
      $added_content .= "<select name='pf_feedback' id='pf_select' class='pf-select'>";

      for ( $i = 0; $i < $pf_n_options; $i++ ) {
        $added_content .= "<option value={$i}>{$pf_options[$i]}</option>";
      }

      $added_content .= "</select>";
      $added_content .= "<button class='pf-button' id='pf_submit_button' data-link='{$link}' data-nonce='{$nonce}' data-post_id='{$post->ID}' style='background-color:{$config["button_color"]}'>Submit Feedback</button>";
      $added_content .= "</form>";
      $added_content .= "</div>";
      $added_content .= "<div class='pf_message' style='display:none'>";
      $added_content .= "</div>";
      $content .= $added_content;
    }
  }
  return $content;
}

add_action( "wp_ajax_pf_submit_feedback", "pf_submit_feedback" );
function pf_submit_feedback() {
  $config = get_option( 'pf_options' );
  $pf_options = $config["labels"];
  $pf_n_options = $config["n_options"];
  $current_user = wp_get_current_user();

  if ( !wp_verify_nonce( $_REQUEST['nonce'], "pf_submit_feedback")) {
    exit( "You're not allowed to do this!" );
  }

  $post_feedback = get_post_meta( $_REQUEST["post_id"], "post_feedback", true );
  $post_feedback_user = get_user_meta( $current_user->ID, "post_feedback_user", true );

  if ( !empty( $post_feedback ) ) {
    $new_post_feedback = $post_feedback;
  } else {
    $new_post_feedback = array_fill( 0, $pf_n_options, 0 );
  }

  if ( !empty( $post_feedback_user ) ) {
    $new_post_feedback_user = $post_feedback_user;
  } else {
    $new_post_feedback_user = array();
  }

  $new_post_feedback[$_REQUEST["feedback_id"]] = $new_post_feedback[$_REQUEST["feedback_id"]] + 1;
  $new_post_feedback_user[$_REQUEST["post_id"]] = 1;

  add_post_meta( $_REQUEST["post_id"], "post_feedback", $new_post_feedback, true ) || update_post_meta( $_REQUEST["post_id"], "post_feedback", $new_post_feedback );
  add_user_meta( $current_user->ID, "post_feedback_user", $new_post_feedback_user, true ) || update_user_meta( $current_user->ID, "post_feedback_user", $new_post_feedback_user );

  $result['type'] = "success";

  if( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
    $result = json_encode( $result );
    echo $result;
  }
  else {
    header( "Location: ".$_SERVER["HTTP_REFERER"] );
  }

  die();
}

add_action( "wp_ajax_nopriv_pf_submit_feedback", "pf_must_login" );
function pf_must_login() {
  echo "You must log in";
  die();
}

add_filter( 'manage_posts_columns', 'pf_column_head' );
function pf_column_head( $defaults ) {
  $config = get_option( 'pf_options' );

  if ( $config["enable"] == 1 ) {
    $defaults['feedback'] = "Feedback";
  }

  return $defaults;
}

add_action( 'manage_posts_custom_column', 'pf_column_content', 10, 2 );
function pf_column_content( $column_name, $post_id ) {

  if ( $column_name == 'feedback' ) {
    $post_feedback = get_post_meta( $post_id, "post_feedback", true );
    $config = get_option( 'pf_options' );
    $pf_options = $config["labels"];
    $colors = $config["colors"];
    $color_count = count($colors);

    $data = array();
    $data[] = array('Label', 'Count', array('role' => 'style'), array('role' => 'tooltip') );

    if ( !empty( $post_feedback ) ) {
      for ($i = 0; $i < count($post_feedback); $i++) {
        $data[] = array( $pf_options[$i], $post_feedback[$i], $colors[$i], $post_feedback[$i] );
      }
    }

    $pf_json = json_encode( $data );

    if ( $post_feedback ) {
      echo "<div id='pf_chart_{$post_id}' class='pf_chart' style='width:200px;height:50px;' data-feedback='{$pf_json}'></div>";
    }
  }
}

add_action( 'load-edit.php', pf_edit_screen );
function pf_edit_screen() {
  $screen = get_current_screen();
  $config = get_option( 'pf_options' );

  if ( 'edit-post' === $screen->id && $config["enable"] == 1 ) {
    add_action( 'in_admin_footer', pf_edit_screen_footer );
  }
}

function pf_edit_screen_footer() {
  $config = get_option( 'pf_options' );
  $colors = $config["colors"];
  $pf_options = $config["labels"];
  $pf_n_options = $config["n_options"];

  echo "<div class='pf-chart-legend'>";

  for ( $i = 0; $i < $pf_n_options; $i++ ) {
    echo "<div class='pf-legend'><div class='pf-colorbox' style='background:{$colors[$i]}'></div><div class='pf-legend-text'>{$pf_options[$i]}</div></div>";
  }

  echo "</div>";
}

add_action( 'admin_enqueue_scripts', 'pf_enqueue_color_picker' );
function pf_enqueue_color_picker( $hook_suffix ) {
  wp_enqueue_style( 'wp-color-picker' );
}
?>
