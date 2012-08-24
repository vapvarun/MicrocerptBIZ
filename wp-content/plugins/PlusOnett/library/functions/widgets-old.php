<?php
/********************************************************************
You can add your widgets in this file and it will affected.
This is the theme related widgets functions file where you can add you created widget code.\
The file is included in functions.php  file of theme root.
********************************************************************/

// =============================== Home Banner ======================================
if(!class_exists('templ_submitarticle'))
{
	class templ_submitarticle extends WP_Widget {
		function templ_submitarticle() {
		//Constructor
			$widget_ops = array('classname' => 'widget home_banner', 'description' => apply_filters('templ_submitarticle_filter','Submit Article') );		
			$this->WP_Widget('widget_templ_submitarticle', apply_filters('templ_submitarticle','T &rarr; Submit Article Button'), $widget_ops);
		}
		function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
			$buttontext = empty($instance['buttontext']) ? '' : apply_filters('widget_buttontext', $instance['buttontext']);
			$description = empty($instance['description']) ? '' : apply_filters('widget_description', $instance['description']);
			?>						
		   
           <div class="submit_article">
				<?php if($buttontext){?> 
				<input type="button" class="article_btn" onclick="window.location.href='<?php echo site_url();?>/?ptype=add_step1'" value="<?php echo $buttontext; ?>" />
				<?php } 
				if($description){
				?>				
				<small><?php echo $description; ?> </small>
				<?php } ?>
			</div> 
              
		<?php
		}
		function update($new_instance, $old_instance) {
		//save the widget
			$instance = $old_instance;		
			$instance['buttontext'] = strip_tags($new_instance['buttontext']);
			$instance['description'] = ($new_instance['description']);
			
			return $instance;
		}
		function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array( 'buttontext' => 'Submit Article','description' => 'Add your article detail click on submit article button') );		
			$buttontext = strip_tags($instance['buttontext']);
			$description = $instance['description'];
 			
		?>
		<p><label for="<?php  echo $this->get_field_id('buttontext'); ?>"><?php _e('Button text','templatic');?>: <input class="widefat" id="<?php  echo $this->get_field_id('buttontext'); ?>" name="<?php echo $this->get_field_name('buttontext'); ?>" type="text" value="<?php echo attribute_escape($buttontext); ?>" /></label></p>
		<p><label for="<?php  echo $this->get_field_id('description'); ?>"><?php _e('Description','templatic');?>: <input class="widefat" id="<?php  echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo attribute_escape($description); ?>" /></label></p>
		
		
		<?php
	}}
	register_widget('templ_submitarticle');
}

// custom taxonomy widget
define('CUSTOM_POST_TYPE1','listing');
define('CUSTOM_CATEGORY_TYPE1','lcategory');
define('CUSTOM_TAG_TYPE1','ltags');
$custom_post_type = CUSTOM_POST_TYPE1;
//======================================
class WP_Widget_Custom_Taxonomy extends WP_Widget {

	function WP_Widget_Custom_Taxonomy() {
		$widget_ops = array('classname' => 'widget_taxonomy', 'description' => 'A list or dropdown of Custom Taxonomy' );		
		$this->WP_Widget('widget_taxonomy', 'PT &rarr; Custom Taxonomy', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
		$c = $instance['count'] ? '1' : '0';
		$h = $instance['hierarchical'] ? '1' : '0';
		$d = $instance['dropdown'] ? '1' : '0';
		$tn = $instance['taxonomy'] ? $instance['taxonomy'] : 'category';
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h, 'taxonomy'=>$tn);
		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Category');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var dropdown = document.getElementById("cat");
	function onCatChange() {
		if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
		}
	}
	dropdown.onchange = onCatChange;
/* ]]> */
</script>
<?php
		} else {
?>	<ul>
<?php
		$cat_args['title_li'] = '';
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
	</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$current_taxonomy = esc_attr( $instance['taxonomy'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		global $custom_post_type;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        
       <p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e( 'Select Taxonomy:' ); ?></label>
		<select id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
        <?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
				$tax = get_taxonomy($taxonomy);
				if ( !$tax->show_tagcloud || empty($tax->labels->name) )
					continue;
		?>
			<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
		<?php endforeach; ?>
		<?php foreach ( get_object_taxonomies($custom_post_type) as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( !$tax->show_tagcloud || empty($tax->labels->name) )
						continue;
		?>
			<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
		<?php endforeach; ?>
        </select></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Show as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
	}

}
register_widget('WP_Widget_Custom_Taxonomy');


class WP_Widget_Custom_Tag_Cloud extends WP_Widget {

	function WP_Widget_Custom_Tag_Cloud() {
		$widget_ops = array('classname' => 'widget_custom_tag_cloud', 'description' => 'Custom Tag Cloud' );		
		$this->WP_Widget('widget_custom_tag_cloud', 'PT &rarr; Custom Tag Cloud', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		} else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags');
			} else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div>';
		wp_tag_cloud( apply_filters('widget_tag_cloud_args', array('taxonomy' => $current_taxonomy) ) );
		echo "</div>\n";
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
		return $instance;
	}

	function form( $instance ) {
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		global $custom_post_type;
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
	<?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
				$tax = get_taxonomy($taxonomy);
				if ( !$tax->show_tagcloud || empty($tax->labels->name) )
					continue;
	?>
		<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
	<?php endforeach; ?>
    <?php foreach ( get_object_taxonomies($custom_post_type) as $taxonomy ) :
				$tax = get_taxonomy($taxonomy);
				if ( !$tax->show_tagcloud || empty($tax->labels->name) )
					continue;
	?>
		<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
	<?php endforeach; ?>
    
	</select></p><?php
	}

	function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];

		return 'post_tag';
	}
}
register_widget('WP_Widget_Custom_Tag_Cloud');
?>