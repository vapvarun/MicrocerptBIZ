=== BAW Better Admin Color Themes ===
Contributors: juliobox
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KJGT942XKWJ6W
Tags: admin, theme, color, icon, scheme
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: trunk

Add more color themes and icons into your admin dashboard ! You can preview theme just clicking on it (JS required).

== Description ==

This plugin adds 4 more colors into your admin/member profile. It changes the "4 squares preview" with a beautiful gradient preview. You can click on it to select the teme : it changes in real time to preview colors !
I added 4 icon themes for your admin menus, same behavior : click on it to preview.

== Installation ==

1. Upload the *"baw-better-admin-color-themes"* to the *"/wp-content/plugins/"* directory
1. Activate the plugin through the *"Plugins"* menu in WordPress
1. Go to your profile page

== Frequently Asked Questions ==

= Can i add more colors? =

Yes, WordPress got a function for this : wp_admin_css_color() (check the codex)
You have to create or copy+modify a existing CSS file to set your colors.

= Can i add more icons? =

Yes, i added a WP like function called : wp_admin_css_icon()
wp_admin_css_icon( $slug, $title, $css_url, $pic_preview_url );
Example of use:
wp_admin_css_icon( 'fugue', _x( 'Fugue', 'admin icon scheme' ), BAWBACT_PLUGIN_URL .  '/css/fugue.css', BAWBACT_PLUGIN_URL .  '/css/images/menu-fugue.png' );

== Screenshots ==

1. The new colors and clickable gradient
1. The new icon sets
1. My menu with "lavender" color and "Fugue" icons

== Changelog ==

= 1.0 =
* 24 mar 2012
* First release

== Upgrade Notice ==

None