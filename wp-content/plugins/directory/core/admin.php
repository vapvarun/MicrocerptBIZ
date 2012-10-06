<?php

/**
* Directory_Core_Admin
*
* @uses Directory_Core
* @copyright Incsub 2007-2011 {@link http://incsub.com}
* @author Ivan Shaovchev (Incsub) {@link http://premium.wpmudev.org}
* @license GNU General Public License (Version 2 - GPLv2) {@link http://www.gnu.org/licenses/gpl-2.0.html}
*/

if(!class_exists('Directory_Core_Admin')):

class Directory_Core_Admin extends Directory_Core {

	/** @var array Holds all capability names, along with descriptions. */
	var $capability_map;

	/**
	* Constructor.
	*/
	function Directory_Core_Admin() { __construct(); }

	function __construct(){

		parent::__construct();

		register_activation_hook( $this->plugin_dir . 'loader.php', array( &$this, 'init_defaults' ) );

		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_menu', array( &$this, 'reorder_menu' ), 999 );

		add_action( 'admin_print_scripts', array( &$this, 'js_print_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'on_enqueue_scripts' ) );

		add_action( 'admin_init', array( &$this, 'welcome_first_time_user' ) );
		add_action( 'admin_init', array( &$this, 'handle_getting_started_redirects') );

		//add user roles to all subsites
		if ( is_multisite() )
		add_action( 'wp', array( &$this, 'add_user_roles' ) );

		add_action( 'wp_ajax_dr_get_caps', array( &$this, 'ajax_get_caps' ) );
		add_action( 'wp_ajax_dr_save', array( &$this, 'ajax_save' ) );

		add_action( 'wp_ajax_nopriv_check_login', array( &$this, 'ajax_check_login' ) );
		add_action( 'wp_ajax_check_login', array( &$this, 'ajax_check_login' ) );

		//IPN script for Paypal
		add_action( 'wp_ajax_nopriv_directory_ipn', array( &$this, 'ajax_directory_ipn' ) );
		add_action( 'wp_ajax_directory_ipn', array( &$this, 'ajax_directory_ipn' ) );

		// Render admin via action hook. Used mainly by modules.
		add_action( 'render_admin', array( &$this, 'render_admin' ), 10, 2 );

		$this->capability_map = array(
		'read_listings'             => __( 'View listings.', $this->text_domain ),
		'publish_listings'          => __( 'Add listings.', $this->text_domain ),
		'edit_published_listings'   => __( 'Edit listings.', $this->text_domain ),
		'delete_published_listings' => __( 'Delete listings.', $this->text_domain ),
		'edit_others_listings'      => __( 'Edit others\' listings.', $this->text_domain ),
		'delete_others_listings'    => __( 'Delete others\' listings.', $this->text_domain ),
		'upload_files'              => __( 'Upload files.', $this->text_domain ),
		);
	}

	/**
	* Initiate admin default settings.
	*
	* @return void
	*/
	function init_defaults( $set_option = true) {
		global $wp_roles;

		//add role of directory member with full access
		$wp_roles->remove_role( "directory_member" );
		$wp_roles->remove_role( "directory_member_paid" );
		$wp_roles->add_role( "directory_member_paid", 'Directory Member Paid', array(
		'read_listings'             => true,
		'publish_listings'          => true,
		'edit_published_listings'   => true,
		'delete_published_listings' => true,
		'upload_files'              => true,
		'read'                      => true,
		) );

		//add role of directory member with limit access
		$wp_roles->remove_role( "directory_member_deny" );
		$wp_roles->remove_role( "directory_member_not_paid" );
		$wp_roles->add_role( "directory_member_not_paid", 'Directory Member Not Paid', array(
		'read'                      => true
		) );

		//set capability for admin
		foreach ( array_keys( $this->capability_map ) as $capability )
		$wp_roles->add_cap( 'administrator', $capability );

		// add option to the autoload list
		if ( $set_option )
		add_option( $this->options_name, array() );

	}


	/**
	* check and add directory roles for all sites
	*
	* @return void
	*/
	function add_user_roles() {
		global $wp_roles;

		if ( !isset( $wp_roles->role_names['directory_member_paid'] ) || !isset( $wp_roles->role_names['directory_member_not_paid'] ) ) {
			$this->init_defaults( false );
		}
	}

	/**
	* Register all admin menues.
	*
	* @return void
	*/
	function admin_menu() {
		$opts = get_option( $this->options_name );
		if ( ( isset( $opts['general_settings']['show_getting_started'] ) && '1' == $opts['general_settings']['show_getting_started'] ) || !$this->_getting_started_complete() ) {
			$menu_page = add_submenu_page( 'edit.php?post_type=directory_listing', __( 'Getting Started', $this->text_domain ), __( 'Getting Started', $this->text_domain ), 'manage_options', 'dr-get_started', array( $this, 'create_getting_started_page' ) );
			// Hook styles
			//add_action( 'admin_print_styles-' .  $menu_page, array( &$this, 'enqueue_styles' ) );
		}

		$settings_page = add_submenu_page( 'edit.php?post_type=directory_listing', __( 'Settings', $this->text_domain ), __( 'Settings', $this->text_domain ), 'edit_users', 'settings', array( &$this, 'handle_settings_page_requests' ) );

	}

	/**
	* Quick hack to reorder CPT menu items
	* so that the welcome page come first.
	*/
	function reorder_menu () {
		$opts = get_option( $this->options_name );
		if ( ( !isset( $opts['general_settings']['show_getting_started'] ) || '0' == $opts['general_settings']['show_getting_started'] ) && $this->_getting_started_complete() )
		return;

		global $menu, $submenu;

		foreach ( $submenu as $idx => $item ) {
			if ( 'edit.php?post_type=directory_listing' != $idx ) continue;
			$tmp = $item[17];
			unset( $item[17] );
			array_unshift( $item, $tmp );
			$submenu[$idx] = $item;
		}
	}

	/**
	* Inject welcome page markup.
	*/
	function create_getting_started_page () {
		global $current_user;
		$dr_tutorial = get_user_meta ( $current_user->ID, 'dr_tutorial', true );
		$dr_tutorial = $dr_tutorial ? $dr_tutorial : array();
		include( $this->plugin_dir . 'ui-admin/getting-started.php' );
	}


	/**
	* Redirect to Getting started page on first load.
	*/
	function welcome_first_time_user () {
		if ( is_network_admin() ) return false; // Not applicable on network pages.
		if ( $this->_getting_started_complete() ) return false; // User already saw this.

		$opts = get_option( $this->options_name );
		if ( isset( $opts['general_settings']['welcome_redirect'] ) and !$opts['general_settings']['welcome_redirect'] ) return false; // Not a first time user, move on.

		//if old version < 2
		if ( get_option( 'dp_options' ) && ! isset( $_POST['install_dir2'] ) )
		return false;

		$opts['general_settings']['welcome_redirect'] = false;
		update_option( $this->options_name, $opts );
		wp_redirect( admin_url( 'admin.php?page=dr-get_started' ) );
		die;
	}


	/**
	* Handle calls from welcome page and record progress.
	*/
	function handle_getting_started_redirects () {
		global $current_user;
		$dr_tutorial = get_user_meta( $current_user->ID, 'dr_tutorial', true );
		$dr_tutorial = $dr_tutorial ? $dr_tutorial : array();

		$dr_intent = isset( $_GET['dr_intent'] ) ? $_GET['dr_intent'] : false ;
		switch ( $dr_intent ) {
			case "settings":
			$dr_tutorial['settings'] = 1;
			update_user_meta( $current_user->ID, 'dr_tutorial', $dr_tutorial );
			wp_redirect( admin_url( 'edit.php?post_type=directory_listing&page=settings' ) );
			exit;
			case "category":
			$dr_tutorial['category'] = 1;
			update_user_meta( $current_user->ID, 'dr_tutorial', $dr_tutorial );
			wp_redirect( admin_url( 'edit-tags.php?taxonomy=listing_category&post_type=directory_listing' ) );
			exit;
			case "listing":
			$dr_tutorial['listing'] = 1;
			update_user_meta( $current_user->ID, 'dr_tutorial', $dr_tutorial );
			wp_redirect( admin_url( 'edit.php?post_type=directory_listing' ) );
			exit;
		}
	}


	/**
	* Quick "are we done yet" check for welcome page.
	*/
	private function _getting_started_complete () {
		global $current_user;
		$dr_tutorial = get_user_meta( $current_user->ID, 'dr_tutorial', true );
		$dr_tutorial = $dr_tutorial ? $dr_tutorial : array();

		if ( isset( $dr_tutorial['settings'] ) && isset( $dr_tutorial['category'] ) && isset( $dr_tutorial['listing'] ) )
		return true;
		else
		return false;
	}

	/**
	* Load scripts on plugin specific admin pages only.
	*
	* @return void
	*/
	function on_enqueue_scripts() {

		wp_enqueue_style( 'dr-admin-styles', $this->plugin_url . 'ui-admin/css/ui-styles.css');

		//including JS scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-form' );

		if ( isset( $_GET['post_type'] ) &&  'directory_listing' == $_GET['post_type'] ) {
			wp_enqueue_script( 'dr_editor', $this->plugin_url . 'ui-admin/js/admin.js', array('jquery') );
		}

		wp_enqueue_script( 'dr-admin-scripts', $this->plugin_url . 'ui-admin/js/ui-scripts.js', array( 'jquery' ) );
	}


	/**
	* Inject basic javascript dataset, for consistency.
	*/
	function js_print_scripts () {
		printf(
		'<script type="text/javascript">
		var _dr_data = {
		"root_url": "%s",
		};
		</script>',
		$this->plugin_url
		);
	}

	/**
	* Handles $_GET and $_POST requests for the settings page.
	*
	* @return void
	*/
	function handle_settings_page_requests() {
		$valid_tabs = array( 'general', 'capabilities', 'ads', 'payments', 'payments-type', 'affiliate', 'shortcodes' );

		if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $valid_tabs ) ) {
			if ( isset( $_POST['save'] ) ) {
				$this->save_admin_options( $_POST );
			}
			$this->render_admin( 'settings-'.$_GET['tab'] );
		} else {
			if ( isset( $_POST['save'] ) ) {
				$this->save_admin_options( $_POST );
			}
			$this->render_admin( 'settings-general' );
		}

		do_action( 'dr_handle_settings_page_requests' );

	}

	/**
	* Ajax callback which gets the post types associated with each page.
	*
	* @return JSON Encoded string
	*/
	function ajax_get_caps() {
		if ( !current_user_can( 'manage_options' ) )
		die(-1);

		global $wp_roles;

		$role = $_POST['role'];

		if ( !$wp_roles->is_role( $role ) )
		die(-1);

		$role_obj = $wp_roles->get_role( $role );

		$response = array_intersect( array_keys( $role_obj->capabilities ), array_keys( $this->capability_map ) );
		$response = array_flip( $response );

		// response output
		header( "Content-Type: application/json" );
		echo json_encode( $response );
		die();
	}

	/**
	* Save admin options.
	*
	* @return void die() if _wpnonce is not verified
	*/
	function ajax_save() {

		check_admin_referer( 'verify' );

		if ( !current_user_can( 'manage_options' ) )
		die(-1);

		// add/remove capabilities
		global $wp_roles;

		$role = $_POST['roles'];

		$all_caps = array_keys( $this->capability_map );
		$to_add = array_keys( $_POST['capabilities'] );
		$to_remove = array_diff( $all_caps, $to_add );

		foreach ( $to_remove as $capability ) {
			$wp_roles->remove_cap( $role, $capability );
		}

		foreach ( $to_add as $capability ) {
			$wp_roles->add_cap( $role, $capability );
		}

		die(1);
	}


	/**
	* Checking login name for register new user.
	*
	* @return
	*/
	function ajax_check_login() {

		if ( "login" == $_REQUEST['type'] ) {
			if ( username_exists( $_REQUEST['login'] ) )
			die('yes');
			else
			die('no');
		} elseif ( "email" == $_REQUEST['type'] ) {
			if ( email_exists( $_REQUEST['email'] ) )
			die('yes');
			else
			die('no');
		}
		die("yes");
	}


	/**
	* IPN script for change user role when Recurring Payment changed status
	*
	* @return void
	*/
	function ajax_directory_ipn() {
		// debug mode for IPN script (please open plugin dir (directory) for writing)
		$debug_ipn = 0;
		if ( 1 == $debug_ipn ) {
			$File = $this->plugin_dir ."debug_ipn.log";
			$Handle = fopen( $File, 'a+' );
			ob_start();
			print_r( date( "H:i:s m.d.y" ) . ' - 01 -' . " POST\r\n" );
			print_r( $_POST );
			$a = ob_get_contents();
			ob_end_clean();
			fwrite($Handle, $a );
		}

		$postdata = "";
		foreach ( $_POST as $key=>$value )
		$postdata .= $key."=".urlencode( $value )."&";

		$postdata .= "cmd=_notify-validate";
		$options = $this->get_options( 'paypal' );

		if ( 'live' == $options['api_url'] )
		$url = "https://www.paypal.com/cgi-bin/webscr";
		else
		$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$args =  array(
		'timeout' => 90,
		'sslverify' => false
		);

		$response = wp_remote_get( $url . "?" . $postdata, $args );

		if( is_wp_error( $response ) ) {
			if ( 1 == $debug_ipn ) {
				ob_start();
				print_r( date( "H:i:s m.d.y" ) . ' - 02 -' . " error with send post\r\n" );
				print_r( "url: " . $url . "\r\n" );
				print_r( $response );
				$a = ob_get_contents();
				ob_end_clean();
				fwrite($Handle, $a );
			}
			die('error with send post');
		} else {
			$response = $response["body"];
		}


		if ( $response != "VERIFIED" ) {
			if ( 1 == $debug_ipn ) {
				ob_start();
				print_r( date( "H:i:s m.d.y" ) . ' - 03 -' . " not VERIFIED\r\n" );
				print_r( $response );
				$a = ob_get_contents();
				ob_end_clean();
				fwrite($Handle, $a );
			}
			die( 'not VERIFIED' );
		}

		if ( $_POST['subscr_id'] ) {
			$user_id = $_POST['custom'];

			$dr_options = get_usermeta( $user_id, 'dr_options' );

			if ( "subscr_payment" == $_POST['txn_type'] ) {

				$key = md5( $_POST['mc_currency'] . "directory_123" . $_POST['mc_gross'] );

				//checking hash keys
				if ( $key != $dr_options['paypal']['key'] ) {
					if ( 1 == $debug_ipn ) {
						ob_start();
						print_r( date( "H:i:s m.d.y" ) . ' - 04 -' . " Conflict Keys:\r\n" );
						print_r( " key from site: " . $dr_options['paypal']['key'] );
						print_r( "key from Paypal: " . $key );
						$a = ob_get_contents();
						ob_end_clean();
						fwrite( $Handle, $a );
					}
					die("conflict key");
				}

				if ( 1 == $debug_ipn ) {
					ob_start();
					print_r( date( "H:i:s m.d.y" ) . ' - 05 -' . " subscr_payment OK\r\n" );
					$a = ob_get_contents();
					ob_end_clean();
					fwrite( $Handle, $a );
				}

				//write subscr_id (profile_id) to user meta
				$dr_options['paypal']['profile_id'] = $_POST['subscr_id'];
				update_user_meta( $user_id, 'dr_options', $dr_options );

				//set role with with access of directory plugin
				wp_update_user( array(  'ID'    => $user_id,
				'role'  => "directory_member_paid" ) );

				//for affiliate subscription
				$affiliate_settings = $this->get_options( 'affiliate_settings' );
				do_action( 'directory_set_paid_member', $affiliate_settings, $user_id, 'recurring' );

			} elseif ( "subscr_cancel" == $_POST['txn_type'] ||
			"subscr_failed" == $_POST['txn_type'] ||
			"subscr_eot" == $_POST['txn_type'] ) {

				if ( 1 == $debug_ipn ) {
					ob_start();
					print_r( date( "H:i:s m.d.y" ) . ' - 06 -' . " other payment status:\r\n" );
					print_r( $_POST['txn_type'] );
					$a = ob_get_contents();
					ob_end_clean();
					fwrite( $Handle, $a );
				}

				//checking profile_id
				if  ( $_POST['subscr_id']  == $dr_options['paypal']['profile_id'] ) {
					//set role with with limeted access of directory plugin
					wp_update_user( array(  'ID'    => $user_id,
					'role'  => "directory_member_not_paid" ) );

				} else {
					if ( 1 == $debug_ipn ) {
						ob_start();
						print_r( date( "H:i:s m.d.y" ) . ' - 07 -' . " wrong profile_id:\r\n" );
						print_r( " profile_id from site: " . $dr_options['paypal']['profile_id'] );
						print_r( "profile_id from Paypal: " . $_POST['subscr_id'] );
						$a = ob_get_contents();
						ob_end_clean();
						fwrite( $Handle, $a );
					}
				}
			}
		}

		die("ok");
	}

	/**
	* Renders an admin section of display code.
	*
	* @param  string $name Name of the admin file(without extension)
	* @param  string $vars Array of variable name=>value that is available to the display code(optional)
	* @return void
	*/
	function render_admin( $name, $vars = array() ) {
		foreach ( $vars as $key => $val )
		$$key = $val;
		if ( file_exists( "{$this->plugin_dir}ui-admin/{$name}.php" ) )
		include "{$this->plugin_dir}ui-admin/{$name}.php";
		else
		echo "<p>Rendering of admin template {$this->plugin_dir}ui-admin/{$name}.php failed</p>";
	}

	/**
	* Save plugin options.
	*
	* @param  array $params The $_POST array
	* @return die() if _wpnonce is not verified
	*/
	function save_admin_options( $params ) {
		if ( wp_verify_nonce( $params['_wpnonce'], 'verify' ) ) {
			/* Remove unwanted parameters */
			unset( $params['_wpnonce'], $params['_wp_http_referer'], $params['save'] );

			//change format for cost to .00
			if ( 'payment_settings' ==  $params['key'] ) {
				if ( isset( $params['recurring_cost'] ) )
				$params['recurring_cost'] = sprintf( "%01.2f", $params['recurring_cost'] );
				if ( isset( $params['one_time_cost'] ) )
				$params['one_time_cost'] = sprintf( "%01.2f", $params['one_time_cost'] );
			}

			/* Update options by merging the old ones */
			$options = $this->get_options();
			$options = array_merge( $options, array( $params['key'] => $params ) );
			update_option( $this->options_name, $options );
		} else {
			die( __( 'Security check failed!', $this->text_domain ) );
		}
	}
}

/* Initiate Admin */
$directory_core = new Directory_Core_Admin();

endif;