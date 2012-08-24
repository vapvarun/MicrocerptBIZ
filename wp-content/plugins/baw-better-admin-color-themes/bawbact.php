<?php
/* Plugin Name: BAW Better Admin Color Themes
 * Plugin URI: http://www.boiteaweb.fr/bact
 * Description: This plugin adds 4 new color themes and 4 icon themes, yes this is new!
 * Version: 1.0
 * Author: Juliobox
 * Author URI: http://www.boiteaweb.fr
 * License: GPLv2
**/

DEFINE( 'BAWBACT_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );
DEFINE( 'BAWBACT_VERSION', '1.0' );

function wp_admin_css_icon( $key, $name, $url, $demo )
{
	global $_wp_admin_css_icons;

	if ( !isset($_wp_admin_css_icons) )
		$_wp_admin_css_icons = array();

	$_wp_admin_css_icons[$key] = (object)array( 'name' => $name, 'url' => $url, 'demo' => $demo );
}

function bawbact_add_my_colors()  
{  
	wp_admin_css_color( 'red', _x( 'Cherry', 'admin color scheme' ), BAWBACT_PLUGIN_URL .  '/css/red.css',
						array( '#aa5555', '#e9cfcf', '#eed1d1', '#ffefef' ) ); 
	wp_admin_css_color( 'green', _x( 'Lawn', 'admin color scheme' ), BAWBACT_PLUGIN_URL .  '/css/green.css',
						array( '#78aa55', '#d9e9cf', '#ddeed1', '#f5ffef' ) ); 
	wp_admin_css_color( 'brown', _x( 'White Coffee', 'admin color scheme' ), BAWBACT_PLUGIN_URL .  '/css/brown.css',
						array( '#aa8655', '#e9decf', '#eee1d1', '#fff8ef' ) ); 
	wp_admin_css_color( 'purple', _x( 'Lavender', 'admin color scheme' ), BAWBACT_PLUGIN_URL .  '/css/purple.css',
						array( '#8d55aa', '#e0cfe9', '#e4d1ee', '#f9efff' ) ); 
	$remove_themes = apply_filters( 'remove_admin_css_color', array() );
	foreach( $remove_themes as $remove_theme ):
		remove_admin_css_color( $remove_theme );
	endforeach;
}  
add_action( 'admin_init', 'bawbact_add_my_colors', 1 ); 

remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

function remove_admin_css_color( $key )
{
	global $_wp_admin_css_colors;
	if ( isset($_wp_admin_css_colors[$key] ) )
		unset( $_wp_admin_css_colors[$key] );
}

function bawbact_add_all_theme_colors_in_css()
{
	global $pagenow, $_wp_admin_css_colors, $_wp_admin_css_icons, $current_user;
	// Init
	$current_color = get_user_option('admin_color', $current_user->ID);
	if ( empty($current_color) )
		$current_color = 'fresh';
	$current_icon = get_user_meta( $current_user->ID, 'admin_icon', true );////
	if ( empty($current_icon) )
		$current_icon = 'default';
	// Code
	if( $pagenow == 'profile.php' ): 
		// Colors
		?>
		<link rel="alternate_icons stylesheet" href="<?php echo esc_url( $_wp_admin_css_icons[$current_icon]->url ); ?>" type="text/css" title="icon_<?php echo esc_attr( $current_icon ); ?>">
		<link rel="alternate_colors stylesheet" href="<?php echo esc_url( $_wp_admin_css_colors[$current_color]->url ); ?>" type="text/css" title="color_<?php echo esc_attr( $current_color ); ?>">
		<?php
		foreach( $_wp_admin_css_colors as $key => $color_info ): 
			if( $key != $current_icon && $color_info->url != '' ) :?>
				<link rel="alternate_colors stylesheet" href="<?php echo esc_url( $color_info->url ); ?>" type="text/css" title="color_<?php echo esc_attr( $key ); ?>" disabled="true">
		<?php endif;
		endforeach; 
		// Icons
		foreach( $_wp_admin_css_icons as $key => $icon_info ): 
			if( $key != $current_icon && $icon_info->url != '' ) :?>
				<link rel="alternate_icons stylesheet" href="<?php echo esc_url( $icon_info->url ); ?>" type="text/css" title="icon_<?php echo esc_attr( $key ); ?>" disabled="true">
			<?php endif;
		endforeach; ?>
		<?php // Scripts  ?>
		<script>
		jQuery(document).ready(function(){
			jQuery('input[name="admin_color"]').click(function(){
				jQuery('link[rel="alternate_colors stylesheet"]').attr('disabled', true);
				if( jQuery(this).val() != '' ) { var $val = jQuery(this).val(); }else{ var $val = jQuery(this).attr('title'); }
				jQuery('link[title="color_'+$val+'"]').attr('disabled', false);
			});
			jQuery('input[name="admin_icon"]').click(function(){
				jQuery('link[rel="alternate_icons stylesheet"]').attr('disabled', true);
				if( jQuery(this).val() != '' ) { var $val = jQuery(this).val(); }else{ var $val = jQuery(this).attr('title'); }
				jQuery('input[name="admin_color"]:checked').click();
				jQuery('link[title="icon_'+$val+'"]').attr('disabled', false);
			});
		});
		</script>
	<?php
	else :
		wp_enqueue_style( 'custom_user_admin_icons', esc_url( $_wp_admin_css_icons[$current_icon]->url ), NULL, BAWBACT_VERSION );
	endif;
}
add_action( 'admin_head', 'bawbact_add_all_theme_colors_in_css', 9999 );

function bawbact__admin_color_scheme_picker() {
	global $_wp_admin_css_colors, $current_user; ?>
<fieldset><legend class="screen-reader-text"><span><?php _e('Admin Color Scheme')?></span></legend>
<?php
$current_color = get_user_option('admin_color', $current_user->ID);
if ( empty($current_color) )
	$current_color = 'fresh';
foreach ( $_wp_admin_css_colors as $color => $color_info ): ?>
<div class="color-option">
	<?php $x=0; 
	for( $x = 0; $x <= count( $color_info->colors )-1; $x++): 
	switch( $x ) {
		case 0: $br = 'border-radius: 10px 0px 0px 10px; '; break;
		case 3: $br = 'border-radius: 0px 10px 10px 0px; '; break;
		default: $br = '';
	}
	echo '<label for="admin_color_'.$color.'"><span style="margin-bottom: 5px;float:left;width:30px; height: 35px; background-color: '.$color_info->colors[$x].'; display: inline-block; '.$br.'">&nbsp;</span></label>';
	if( isset( $color_info->colors[($x+1)] ) ) {
		echo '<label for="admin_color_'.$color.'"><span style="margin-bottom: 5px;float:left;width:20px; height: 35px; background-image: -ms-linear-gradient(center left, '.$color_info->colors[$x].', '.$color_info->colors[($x+1)].'); 	background-image: -moz-linear-gradient(center left, '.$color_info->colors[$x].', '.$color_info->colors[($x+1)].'); 	background-image: -o-linear-gradient(center left, '.$color_info->colors[$x].', '.$color_info->colors[($x+1)].'); 	background-image: -webkit-gradient(linear, left top, left top, from('.$color_info->colors[$x].'), to('.$color_info->colors[($x+1)].')); 	background-image: -webkit-linear-gradient(center left, '.$color_info->colors[$x].', '.$color_info->colors[($x+1)].'); 	background-image: linear-gradient(center left, '.$color_info->colors[$x].', '.$color_info->colors[($x+1)].') background-color: '.$color_info->colors[$x].'; display: inline-block;">&nbsp;</span></label>';
	}
	?>
	<?php endfor; ?>
	<input name="admin_color" id="admin_color_<?php echo $color; ?>" type="radio" value="<?php echo esc_attr($color) ?>" class="" <?php checked($color, $current_color); ?> />
	<label for="admin_color_<?php echo $color; ?>"><?php echo $color_info->name ?></label>
</div>
	<?php endforeach; ?>
</fieldset>
<?php
}
add_action( 'admin_color_scheme_picker', 'bawbact__admin_color_scheme_picker' );

function bawbact__admin_icon_scheme_picker() {
	global $_wp_admin_css_icons, $current_user; ?>
</td></tr><tr><th scope="row"><?php _e('Admin Icon Scheme')?></th>
<td>
<fieldset><legend class="screen-reader-text"><span><?php _e('Admin Icon Scheme')?></span></legend>
<?php
$current_icon = get_user_meta( $current_user->ID, 'admin_icon', true );
if ( empty($current_icon) )
	$current_icon = 'default';
foreach ( $_wp_admin_css_icons as $icon => $icon_info ): ?>
	<input name="admin_icon" id="admin_icon_<?php echo $icon; ?>" type="radio" value="<?php echo esc_attr($icon) ?>" class="" <?php checked($icon, $current_icon); ?> />
	<label for="admin_icon_<?php echo $icon; ?>"><?php echo $icon_info->name ?> 
	<?php
		echo '<div style="overflow:hidden; height: 64px; width: 360px"><img src="'.esc_url( $icon_info->demo ) . '" /></div></label>';
endforeach; ?>
</fieldset>
<?php
}
add_action( 'admin_color_scheme_picker', 'bawbact__admin_icon_scheme_picker' );

function bawbact__edit_user_profile_update( $user_id )
{
	update_user_meta( $user_id, 'admin_icon', sanitize_html_class( $_POST['admin_icon'], 'default' ) );
}
add_action( 'personal_options_update', 'bawbact__edit_user_profile_update' );

function bawbact_add_my_icons()  
{  
	wp_admin_css_icon( 'default', sprintf( _x( 'WordPress %s', 'admin icon scheme' ), $GLOBALS['wp_version'] ), '', admin_url( '/images/menu.png' ) );
	wp_admin_css_icon( 'fugue', _x( 'Fugue', 'admin icon scheme' ), BAWBACT_PLUGIN_URL .  '/css/fugue.css', BAWBACT_PLUGIN_URL .  '/css/images/menu-fugue.png' );
	wp_admin_css_icon( 'silk', _x( 'Silk', 'admin icon scheme' ), BAWBACT_PLUGIN_URL .  '/css/silk.css', BAWBACT_PLUGIN_URL .  '/css/images/menu-silk.png' );
	wp_admin_css_icon( 'tangomax', _x( 'Tango + Icon32', 'admin icon scheme' ), BAWBACT_PLUGIN_URL .  '/css/tango-max.css', BAWBACT_PLUGIN_URL .  '/css/images/menu-tango.png' );
	wp_admin_css_icon( 'tango2max', _x( 'Tango 2 + Icon32', 'admin icon scheme' ), BAWBACT_PLUGIN_URL .  '/css/tango2-max.css', BAWBACT_PLUGIN_URL .  '/css/images/menu-tango2.png' );
}
add_action( 'admin_init', 'bawbact_add_my_icons', 1 ); 

function bawbact_plugin_action_links( $links, $file )
{
	if ( strstr( __FILE__, $file ) != '' ) {
		$settings_link = '<a href="' . admin_url( 'profile.php' ) . '">' . __( 'Profile' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'bawbact_plugin_action_links', 10, 2 );
?>