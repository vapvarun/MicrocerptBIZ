<?php
/*
Plugin Name: iDashboard
Description: Plugin to customise admin pages menu bar, admin bar for everyone except superadmin. Also sets up site registration wigit for users with no site. <strong>Note: This plugin requires a minimum of WordPress 3.1. If you are running a version less than that, please upgrade your WordPress install now.</strong>
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}






// Function that output's the contents of the dashboard widget
function subscription_display_widget ($type, $receiver_id) {
	error_log ("function subscription_display_widget type: $type, id: $receiver_id");
	// lets pretend we have input
	
	// example blog
	//$type = 2; // 1 for cat - 2 for author/blog
	// $rec_id = 72; // id of the cat or blog

	// example category
	// $type = 1; // 1 for cat - 2 for author/blog
	// $rec_id = 2482; // id of the cat or blog

	switch ($type) {
		case '1':
			// category
			switch_to_blog(1);
			$args=array('category' => $receiver_id);
			$posts = get_posts($args);
			// lets just take the top 5 posts
			$posts = array_slice($posts, 0, 5);
			
			
			// 'http://www.microcerpt.com/blog/category/' . $value_array->slug . '/' ) );
			// TODO: build the array for the  widget factory from members subsciptions for authors/blogs and cats
			// TODO: add slug to the array being given to the WidgetFactory and then add to the subscription_display_widget function input. perhaps just make this extra variable '$more_link and that way it wont be blank for other types.
			
			
			$more_link = get_category_link( $receiver_id );
			restore_current_blog();
			
			// $more_link = 'http://www.microcerpt.com/blog/category/' . $slug;
			
			break;
		case '2':
			$more_link = get_blogaddress_by_id($receiver_id);
			// blog (author)
			switch_to_blog($receiver_id);
			$posts_query = new WP_Query( array(
			  'post_type' => 'post', 
			  'post_status' => 'publish',
			  'posts_per_page' => 5,
			  'orderby' => 'date',
			  'order' => 'DESC'
			) );
			$posts =& $posts_query->posts;
			restore_current_blog();
			break;
		
		default:
			# code...
			break;
	}
	
	

	if ( $posts && is_array( $posts ) ) {
	  // Output posts
		$list = array();
		foreach( $posts as $post ) {
			setup_postdata($post); // for cats
		    // $url = $post->ID ); // The URL to the "Edit" post page
		    $title = get_the_title( $post->ID ); // The title of the post
		    $chars = 30; // Our character limit
			// $item = "<h4><a href='$url' title='" . sprintf( __( 'Edit &#8220;%s&#8221;' ), esc_attr( $title ) ) . "'>" . esc_html($title) . "</a> <abbr title='" . get_the_time(__('Y/m/d g:i:s A'), $post) . "'>" . get_the_time( get_option( 'date_format' ), $post ) . '</abbr></h4>';
			if ( $the_content = preg_split( '#\s#', strip_tags( $post->post_content ), $chars+1 , PREG_SPLIT_NO_EMPTY ) )
			    $item = '<p>' . join( ' ', array_slice( $the_content, 0, $chars ) ) . ( $chars < count( $the_content ) ? '&hellip;' : '' );
			$item .= '<a href="' . $post->guid . '"><br />Read More</a>' . '</p>' ;
			$list[] = $item;
		} // End the foreach loop
		?>
		<ul>
		      <li><?php echo join( "</li>\n<li>", $list ); ?></li>
		</ul>
		<ul>
			<li><a href="<?php echo $more_link ; ?>">More from this source.</a></li>
		</ul>
		<?php
	} else{
	  _e('There are no published posts.');
	}

}



//$GLOBALS['widgetinput'] = array('one','24234232','3','4','5');

// function __construct() {
// $this->arr = array(array('a' => '15', 'b' => 'elephant'),array('a' => '54', 'b' => 'zebra'),array('a' => '151', 'b' => 'zippo')); // example array of things to hand out to widgets

// function nextWidget() {
// $closure = create_function('$a', 'echo widgetOutput(' . $next['a'] . ',' . $next['b'] .  ');');

/** 
		BUild cat data
**/ 


add_action('wp_dashboard_setup', 'prepare_data' );


function prepare_data () {
	$current_user = wp_get_current_user();

	$mycategories_object = get_subscriptions ($current_user->ID, 1);
	$mysubs = array ();

	if (!$mycategories_object) {
		error_log ('iDashboard --> get_subscriptions ($member_id = '.$current_user->ID.', $type_id = 1) returning 0 so there are no cat subs!');
	} else {
		foreach ($mycategories_object as $key => $value_array) {
			foreach ($value_array as $mykey => $myvalue) {
				error_log ("iDashboard -- $mykey => $myvalue");
			}
			array_push ($mysubs, array('type' => '1', 'id' => $value_array->receiver_id, 'name' => $value_array->name));
		}
	}

	
	// author/blog subs
	$author_subs_object = get_author_subs_with_names ($current_user->ID);
	
	if (!$author_subs_object) {
		error_log ('iDashboard --> get_author_subs_with_names ($member_id = '.$current_user->ID.') returning 0 so there are no author subs!');
	} else {
		foreach ($author_subs_object as $key => $value_array) {
			array_push ($mysubs, array('type' => '2', 'id' => $value_array['id'], 'name' => $value_array['name']));
		}
	}
	
	$GLOBALS['iDashboard_subs'] = $mysubs;
	
}

function widgetOutput($type, $receiver_id) {
    return 'type: ' . $type . ' and ID: ' . $receiver_id;
}

class WidgetFactory {

    var $arr;
    var $count;

    function __construct() {
//		$this->arr = array(array('type' => '1', 'id' => '2482'),array('type' => '1', 'id' => '2513'),array('type' => '2', 'id' =>  '71'));
		$this->arr = $GLOBALS['iDashboard_subs'];

		$this->size = count($this->arr);
		$this->count = 0;
    }

    function produceWidgets() {
        while ( $this->nextWidget() ) { ; }
        return true;
    }

    function nextWidget() {
        if ( $this->count < $this->size ) {
            $this->count++;
            $next = array_shift($this->arr);
            // wp_add_dashboard_widget('mywidget' . $this->count, 'Widget Number ' .$this->count, $this->nextWidget());
            wp_add_dashboard_widget('mywidget' . $this->count, $next['name'], $this->makeWidget($next));
            // wp_add_dashboard_widget('mywidget' . $this->count, ' ', $this->nextWidget());
            return true;
        } else {
            return false;
        }
    }

    function makeWidget($next) {
		$closure = create_function('$a', 'echo subscription_display_widget (' . $next['type'] . ',' . $next['id']. ');');
		return $closure;
    }
}

// hook it up to the action hook
function add_dashboard_widgets() {
    $factory = new WidgetFactory;
    $factory->produceWidgets();
}

// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );


?>
