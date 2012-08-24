=== WP Report Error ===
Contributors: DaddyDesign
Tags: email friend, report error, error, page error, contact, webmaster ,report bug
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YKY7SHDT8GTQG
Requires at least: 2.8
Tested up to: 3.0.1
Version: 1.6
Stable tag: 1.6

Plugin that inserts "WP report error" link into page.

== Description ==

This plugin is to let the user inform the wordpress owner/webmaster that there is an error on a page or post.  The plugin inserts a "report error" link on every page/post (or you can insert into individual pages/post). When the link is clicked, a small contact form will pop up, the user then can put in a optional message and press submit. The owner/webmaster will receive the email with the exact page that the error is located on and a message from user.

Plugin Created By <a href="http://www.daddydesign.com" title="daddydesign.com">DaddyDesign</a>.
For Plugin Information please visit <a href="http://www.daddydesign.com/wordpress/report-error-wp-plugin/" title="DaddyDesign Report Error Plugin">WP Report Error Plugin</a>.

= Demo =
You can check the demo at
<a href="http://yourfreelayout.com/" title="Wordpress Free Themes">Free Wordpress Themes</a>.

= Our Other Plugins =
1. <a href="http://www.daddydesign.com/wordpress/social-toolbar-wordpress-plugin/" target="_blank" title="wp social toolbar plugin">WP Social Toolbar</a>
2. <a href="http://www.daddydesign.com/wordpress/javascript-detect-wordpress-plugin/" target="_blank" title="WP Javascript Detect">WP Javascript Detect</a>

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `wp-report-error` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Or you can add the following code to your theme PHP files:
   <?php if(function_exists('ReportPageErrors')){ echo ReportPageErrors(); } ?>
   Or simply add [WPRError] shortcode in the wp-admin page or post html editor.

To protect against spams <a href="http://wordpress.org/extend/plugins/akismet/" title="Akismet">Akismet</a> is needed to be installed and configured using your <a href="http://akismet.com/get/">Akismet.com API key</a> 

== Demo ==
If you have any problems with this plugin or need help, please visit <a href="http://www.daddydesign.com/wordpress/report-error-wp-plugin/" title="DaddyDesign Report Error Plugin">WP Report Error Plugin</a>.

You can check the demo at
<a href="http://yourfreelayout.com/" title="Wordpress Free Themes">Free Wordpress Themes</a>.

== Screenshots ==
1. WP Report Error PopUp Form.
2. WP Report Error settings.

== Changelog ==
-Version 1.0 First Launch.
-Version 1.2 Added Language Support.
-Version 1.5 Spam issue solved using <a href="http://wordpress.org/extend/plugins/akismet/" title="Akismet">Akismet</a> Wordpress plugin.
-Version 1.6 Added Two Options in Plugin option page to hide icon and email field.