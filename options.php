<?php
/* ------------------ */
/* theme options page */
/* ------------------ */

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

// Register Settings (http://codex.wordpress.org/Function_Reference/register_setting)
function theme_options_init(){
	register_setting( 'kb_options', 'kb_theme_options', 'kb_validate_options' );
}

// Create Site at the Dashboard
function theme_options_add_page() {
	add_theme_page('Theme-Options', 'Theme-Options', 'edit_theme_options', 'theme-optionen', 'kb_theme_options_page' );
}

// Create Options-Page
function kb_theme_options_page() {
global $select_options, $radio_options;
if ( ! isset( $_REQUEST['settings-updated'] ) )
	$_REQUEST['settings-updated'] = false; ?>

<div class="wrap"> 
<?php screen_icon(); ?><h2>Theme-Options for <?php bloginfo('name'); ?></h2> 

<?php if ( false !== $_REQUEST['settings-updated'] ) : ?> 
<div class="updated fade">
	<p><strong>Options saved!</strong></p>
</div>
<?php endif; ?>

  <form method="post" action="options.php">
	<?php settings_fields( 'kb_options' ); ?>
    <?php $options = get_option( 'kb_theme_options' ); ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Facebook-Link</th>
        <td><input type="text" id="kb_theme_options[facebook]" class="medium-text" name="kb_theme_options[facebook]" value="<?php esc_attr_e( $options['facebook'] ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Twitter-Link</th>
        <td><input type="text" id="kb_theme_options[twitter]" class="medium-text" name="kb_theme_options[twitter]" value="<?php esc_attr_e( $options['twitter'] ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">GooglePlus-Link</th>
        <td><input type="text" id="kb_theme_options[googleplus]" class="medium-text" name="kb_theme_options[googleplus]" value="<?php esc_attr_e( $options['googleplus'] ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Youtube-Link</th>
        <td><input type="text" id="kb_theme_options[youtube]" class="medium-text" name="kb_theme_options[youtube]" value="<?php esc_attr_e( $options['youtube'] ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">LinkedIn-Link</th>
        <td><input type="text" id="kb_theme_options[linkedin]" class="medium-text" name="kb_theme_options[linkedin]" value="<?php esc_attr_e( $options['linkedin'] ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">About Me</th>
        <td><textarea id="kb_theme_options[aboutme]" class="large-text" cols="40" rows="10" name="kb_theme_options[aboutme]" /><?php esc_attr_e( $options['aboutme'] ); ?></textarea></td>
      </tr> 
      <tr valign="top">
        <th scope="row">Copyright</th>
        <td><textarea id="kb_theme_options[copyright]" class="large-text" cols="40" rows="10" name="kb_theme_options[copyright]" /><?php esc_attr_e( $options['copyright'] ); ?></textarea></td>
      </tr>  
      <tr valign="top">
        <th scope="row">Google Analytics</th>
        <td><textarea id="kb_theme_options[analytics]" class="large-text" cols="40" rows="10" name="kb_theme_options[analytics]"><?php echo esc_textarea( $options['analytics'] ); ?></textarea></td>
      </tr>
    </table>
    
    <!-- submit -->
    <p class="submit"><input type="submit" class="button-primary" value="Einstellungen speichern" /></p>
  </form>
</div>

<?php }
// Strip HTML-Code (if wanted)
// http://codex.wordpress.org/Function_Reference/wp_filter_nohtml_kses
function kb_validate_options( $input ) {
	// $input['copyright'] = wp_filter_nohtml_kses( $input['copyright'] );
	return $input;
}
?>