<?php 

add_action( 'admin_menu', 'pf_admin_menu' );
function pf_admin_menu() {
  add_options_page( PF_PLUGIN_NAME.' Options', PF_PLUGIN_NAME.' Options', 'manage_options', 'pf_settings', 'pf_config' );
}

function pf_admin_tabs( $current = 'general' ) {
  $tabs = array( 'general' => 'General Settings');

  echo "<div class='wrap'>";
  screen_icon( 'plugins' );
  echo "<h2>" . PF_PLUGIN_NAME . " Options</h2>";
  echo "<h2 class='nav-tab-wrapper'>";

  foreach ( $tabs as $tab => $name ) {
    $class = ( $tab == $current ) ? ' nav-tab-active' : '';
    echo "<a class='nav-tab$class' href='?page=pf_settings&tab=$tab'>$name</a>";
  }

  echo "</h2>";
}

function pf_config() {
  $pf_options = get_option('pf_options');
?>

<?php if ( ( $_POST['posted'] == 1 ) && ( is_admin() ) && wp_verify_nonce( $_POST['_wpnonce'] ) ): ?>
  <div id="message" class="updated" > Settings saved successfully </div>
<?php 

  if ( isset ( $_GET['tab'] ) )
    $tab = $_GET['tab'];
  else
    $tab = "general";

  switch ( $tab ) {
    case "general":
      $enable = $_POST['enable'];
      $n_options = nl2br(htmlentities($_POST['n_options'], ENT_QUOTES, 'UTF-8'));
      $labels = $_POST['labels'];
      $colors = $_POST['colors'];
      $button_color = $_POST['button_color'];

      $pf_options["enable"] = $enable;
      $pf_options["n_options"] = $n_options;
      $pf_options["labels"] = $labels;
      $pf_options["colors"] = $colors;
      $pf_options["button_color"] = $button_color;

      break;
  }

  update_option( 'pf_options', $pf_options );
?>  

<?php else:  ?>
<?php endif; ?> 

<?php if ( isset ( $_GET['tab'] ) ) pf_admin_tabs( $_GET['tab'] ); else pf_admin_tabs( 'general' ); ?>

<form method="post" action="">
<?php

wp_nonce_field();

if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
else $tab = 'general';

switch ( $tab ) {
  case "general" :
    pf_general_option_content();
    break;
}

?>

  <input type="hidden" value="1" name="posted" />
  <input type="Submit" value="Save Changes" class="button-primary" />
</form>

<?php
}

function pf_general_option_content() {
  $config = get_option( 'pf_options' );
  ?>  

  <table class="form-table" id="table-general">
    <tr valign="top">
      <th scope="row">Enable Post Feedback</th>
      <td>
        <input id="pf_enable" type="checkbox" name="enable" value=1 <?php echo ( 1 == $config["enable"] ? "checked" : "" ) ?> />
      </td>
    </tr>

    <tr valign="top">
      <th scope="row">Number of Options</th>
      <td>
        <select name="n_options" id="n_options">
          <option value="2" <?php echo ( 2 == $config["n_options"] ? "selected" : "" ) ?>>2</option>
          <option value="3" <?php echo ( 3 == $config["n_options"] ? "selected" : "" ) ?>>3</option>
          <option value="4" <?php echo ( 4 == $config["n_options"] ? "selected" : "" ) ?>>4</option>
          <option value="5" <?php echo ( 5 == $config["n_options"] ? "selected" : "" ) ?>>5</option>
        </select>
      </td>
    </tr>

    <tr valign="top" class="option">
      <th scope="row">Label 1</th>
      <td>
        <input type="text" name="labels[]" value="<?php echo ( empty( $config['labels']['0'] ) ? "" : $config['labels']['0'] ) ?>" /> <input type="text" value="<?php echo ( empty( $config['colors']['0'] ) ? "" : $config['colors']['0'] ) ?>" class="pf-color-picker" name="colors[]" />
      </td>
    </tr>

    <tr valign="top" class="option">
      <th scope="row">Label 2</th>
      <td>
        <input type="text" name="labels[]" value="<?php echo ( empty( $config['labels']['1'] ) ? "" : $config['labels']['1'] ) ?>" /> <input type="text" value="<?php echo ( empty( $config['colors']['1'] ) ? "" : $config['colors']['1'] ) ?>" class="pf-color-picker" name="colors[]" />
      </td>
    </tr>

    <tr valign="top" class="option" style="display:none;">
      <th scope="row">Label 3</th>
      <td>
        <input type="text" name="labels[]" value="<?php echo ( empty( $config['labels']['2'] ) ? "" : $config['labels']['2'] ) ?>" /> <input type="text" value="<?php echo ( empty( $config['colors']['2'] ) ? "" : $config['colors']['2'] ) ?>" class="pf-color-picker" name="colors[]" />
      </td>
    </tr>

    <tr valign="top" class="option" style="display:none;">
      <th scope="row">Label 4</th>
      <td>
        <input type="text" name="labels[]" value="<?php echo ( empty( $config['labels']['3'] ) ? "" : $config['labels']['3'] ) ?>" /> <input type="text" value="<?php echo ( empty( $config['colors']['3'] ) ? "" : $config['colors']['3'] ) ?>" class="pf-color-picker" name="colors[]" />
      </td>
    </tr>

    <tr valign="top" class="option" style="display:none;">
      <th scope="row">Label 5</th>
      <td>
        <input type="text" name="labels[]" value="<?php echo ( empty( $config['labels']['4'] ) ? "" : $config['labels']['4'] ) ?>" /> <input type="text" value="<?php echo ( empty( $config['colors']['4'] ) ? "" : $config['colors']['4'] ) ?>" class="pf-color-picker" name="colors[]" />
      </td>
    </tr>

    <tr valign="top">
      <th scope="row">Button Color</th>
      <td>
        <input type="text" value="<?php echo ( empty( $config['button_color'] ) ? "#0074A2" : $config['button_color'] ) ?>" class="pf-color-picker" name="button_color" />
      </td>
    </tr>

  </table>
<?php
}
?>
