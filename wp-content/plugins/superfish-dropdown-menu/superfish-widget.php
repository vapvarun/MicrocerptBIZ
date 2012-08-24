<?php 

class shailan_SFWidget extends WP_Widget {
    /** constructor */
    function shailan_SFWidget() {
	
		$name =			'Superfish Dropdown Menu';
		$desc = 		'Dropdown page/category menu';
	
		$id_base = 		'superfish_widget';
		$css_class = 	'shailan-sf-dropdown';
		
		$alt_option = 	'widget_superfish_navigation'; 
	
	
		$widget_ops = array(
			'classname' => $css_class,
			'description' => __( $desc, 'shailan-sf-dropdown' ),
		);
		
		$this->WP_Widget($id_base, __($name, 'superfish'), $widget_ops);
		$this->alt_option_name = $alt_option;
		
		add_action( 'wp_head', array(&$this, 'styles'), 10, 1 );	
		add_action( 'wp_footer', array(&$this, 'footer'), 10, 1 );	
		
		$this->defaults = array(
			'title' => '',
			'type' => 'Pages',
			'exclude' => '',
			'home' => 'on',
			'login' => 'off',
			'admin' => 'off',
			'shadows' => 'off',
			'skin' => 'demo.css',
			'style' => '',
			'min_width' => '10em',
			'max_width' => '22em'
		);
    }
	
	
    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
		
		if(! isset($instance['home']) ){ $instance['home'] = 'off'; }
		if(! isset($instance['login']) ){ $instance['login'] = 'off'; }	
		if(! isset($instance['admin']) ){ $instance['admin'] = 'off'; }	
		if(! isset($instance['shadows']) ){ $instance['shadows'] = 'off'; }	
		
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
        ?>
              <?php echo $before_widget; ?>

					<ul class="sf-menu <?php echo $style; ?>" >
					
					<?php if('on' == $home){ ?>						
						<li class="page_item cat-item blogtab <?php if ( is_front_page() && !is_paged() ){ ?>current_page_item current-cat<?php } ?>"><a href="<?php echo get_option('home'); ?>/"><span><?php _e('Home', 'shailan-sf-dropdown'); ?></span></a></li>	
					<?php } ?>
							
					
					<?php if($type == 'Pages'){ ?>
					
						<?php 
						
						wp_list_pages(array(
								'sort_column'=>'menu_order',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); ?>
							
					<?php } else { ?>
					
						<?php 
						wp_list_categories(array(
								'order_by'=>'name',
								'depth'=>'4',
								'title_li'=>'',
								'exclude'=>$exclude
								)); ?>			
							
					<?php } ?>
					
						<?php if('on' == $admin){ wp_register('<li class="admintab">','</li>'); } if('on' == $login){ ?><li class="page_item"><?php wp_loginout(); ?><?php } ?>
						
					</ul>
					
					<div style="clear:both;"></div>
					
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
		if(! isset($old_instance['home']) ){ $old_instance['home'] = 'off'; }
		if(! isset($old_instance['login']) ){ $old_instance['login'] = 'off'; }	
		if(! isset($old_instance['admin']) ){ $old_instance['admin'] = 'off'; }	
		if(! isset($old_instance['shadows']) ){ $old_instance['shadows'] = 'off'; }	
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		if(! isset($instance['home']) ){ $instance['home'] = 'off'; }
		if(! isset($instance['login']) ){ $instance['login'] = 'off'; }	
		if(! isset($instance['admin']) ){ $instance['admin'] = 'off'; }	
		if(! isset($instance['shadows']) ){ $instance['shadows'] = 'off'; }	
		
		$widget_options = wp_parse_args( $instance, $this->defaults );
		extract( $widget_options, EXTR_SKIP );
		
        ?>
			
		<p><?php _e('Type:'); ?> <label for="Pages"><input type="radio" id="Pages" name="<?php echo $this->get_field_name('type'); ?>" value="Pages" <?php if($type=='Pages'){ echo 'checked="checked"'; } ?> /> <?php _e('Pages', 'shailan-sf-dropdown'); ?></label> <label for="Categories"><input type="radio" id="Categories" name="<?php echo $this->get_field_name('type'); ?>" value="Categories" <?php if($type=='Categories'){ echo 'checked="checked"'; } ?>/> <?php _e('Categories', 'shailan-sf-dropdown'); ?></label></p>
			
	<p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('home'); ?>" name="<?php echo $this->get_field_name('home'); ?>"<?php checked( $home, 'on' ); ?> />
		<label for="<?php echo $this->get_field_id('home'); ?>"><?php _e( 'Add homepage link' , 'shailan-sf-dropdown' ); ?></label><br />
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('login'); ?>" name="<?php echo $this->get_field_name('login'); ?>"<?php checked( $login, 'on'); ?> />
		<label for="<?php echo $this->get_field_id('login'); ?>"><?php _e( 'Add login/logout' , 'shailan-sf-dropdown' ); ?></label><br />
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('admin'); ?>" name="<?php echo $this->get_field_name('admin'); ?>"<?php checked( $admin, 'on' ); ?> />
		<label for="<?php echo $this->get_field_id('admin'); ?>"><?php _e( 'Add Register/Site Admin' , 'shailan-sf-dropdown' ); ?></label><br />
	</p>
	
		<p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude:', 'shailan-sf-dropdown'); ?> <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" /></label><br /> 
		<small>Page IDs, separated by commas.</small></p>
			
		<p><label for="<?php echo $this->get_field_id('skin'); ?>"><?php _e('Skin:', 'shailan-sf-dropdown'); ?>  <?php 
		
		// http://www.codewalkers.com/c/a/File-Manipulation-Code/List-files-in-a-directory-no-subdirectories/

			echo "<select name='".$this->get_field_name('skin')."' id='".$this->get_field_id('skin')."'>";
			echo "<option value='no-theme' ".selected( $skin, 'no-theme', false).">No theme</option>";
			
			//The path to the style directory
			$dirpath = plugin_dir_path(__FILE__) . 'skins/';	
			
			$dh = opendir($dirpath);
			
			while (false !== ($file = readdir($dh))) {
			//Don't list subdirectories
				if (!is_dir("$dirpath/$file")) {
					//Truncate the file extension and capitalize the first letter
					echo "<option value='$file' ".selected($skin, $file, false).">" . htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $file))) . '</option>';
				}
			}
			
			closedir($dh); 
		echo "</select>";
		
		?> </label><br /> 
			<small><?php _e('Menu theme.', 'shailan-sf-dropdown'); ?></small></p>
			
		<p>		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('shadows'); ?>" name="<?php echo $this->get_field_name('shadows'); ?>"<?php checked( $shadows, 'on' ); ?> />
		<label for="<?php echo $this->get_field_id('shadows'); ?>"><?php _e( 'Use shadows' , 'shailan-sf-dropdown' ); ?></label><br /></p>
			
		<p><label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', 'shailan-sf-dropdown'); ?>
			<select name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>" >
				<option value='' <?php selected( $style, ''); ?> >Dropdown</option>
				<option value='sf-vertical' <?php selected( $style, 'sf-vertical'); ?> >Vertical</option>
				<option value='sf-navbar' <?php selected( $style, 'sf-navbar'); ?> >Navbar</option>
			</select>
		</label><br />
		<small><?php _e('Menu style.', 'shailan-sf-dropdown'); ?></small></p>
		
		<p><label for="<?php echo $this->get_field_id('min_width'); ?>"><?php _e('Minimum Width:', 'shailan-sf-dropdown'); ?> <input id="<?php echo $this->get_field_id('min_width'); ?>" name="<?php echo $this->get_field_name('min_width'); ?>" type="text" value="<?php echo $min_width; ?>" size="6" />em</label><br /> 
		<small>Minimum width of sub menus.</small></p>
		
		<p><label for="<?php echo $this->get_field_id('max_width'); ?>"><?php _e('Maximum width:', 'shailan-sf-dropdown'); ?> <input id="<?php echo $this->get_field_id('max_width'); ?>" name="<?php echo $this->get_field_name('max_width'); ?>" type="text" value="<?php echo $max_width; ?>" size="6" />em</label><br /> 
		<small>Maximum width of sub menus.</small></p>
			
<div class="widget-control-actions alignright">
<p><small><a href="http://shailan.com/wordpress/plugins/superfish-dropdown-menu/"><?php esc_attr_e('Visit plugin site', 'shailan-sf-dropdown'); ?></a></small></p>
</div>
			
        <?php 
	}
	
	/** Adds ID based dropdown menu skin to the header. */
	function styles(){
		
		if(!is_admin()){
		
		$all_widgets = $this->get_settings();
		
		foreach ($all_widgets as $key => $sfdropdown){
			$widget_id = $this->id_base . '-' . $key;		
			if(is_active_widget(false, $widget_id, $this->id_base)){
		
				$skin = $sfdropdown['skin'];		
		
				if('no-theme'!=$skin){
					echo "\n\t<link rel=\"stylesheet\" href=\"".shailan_SFdropdown::get_plugin_directory()."/skin.php?widget_id=".$key."&skin=".strtolower($skin)."\" type=\"text/css\" media=\"screen\"  />";
				}
			}
		}
		
		}
	}
	
	/** Adds ID based activation script to the footer */
	function footer(){
		
		if(!is_admin()){
		
		$all_widgets = $this->get_settings();
		
		foreach ($all_widgets as $key => $sfdropdown){
		
			$widget_id = $this->id_base . '-' . $key;
		
			if(is_active_widget(false, $widget_id, $this->id_base)){
			
				$type = $sfdropdown['type'];
						
				if($type=='Pages'){
					$path_class = 'current_page_item'; 
				} elseif($type == 'Categories'){
					$path_class = 'current-cat';  
				};
				
				$min_width = $sfdropdown['min_width'];
				if(empty($min_width)){$min_width = 10;};
				
				$max_width = $sfdropdown['max_width'];
				if(empty($max_width)){$max_width = 27;};
				
				$shadows = $sfdropdown['shadows'];
				if(empty($shadows)){ $shadows = 'false'; } else { $shadows = 'true'; }
			
			?>
			<script type="text/javascript"> 
			
			// Dom Ready
			jQuery(document).ready(function($) {

				opts = {          
					pathClass:     '<?php echo $path_class ?>',
					pathLevels:    0,                  
					delay:         800,
					animation:     {opacity:'show'},
					speed:         'normal',
					autoArrows:    true,
					dropShadows:   <?php echo $shadows ?>,
					disableHI:     true
				};

				jQuery('#<?php echo $widget_id; ?> ul.sf-menu').supersubs({ 
					minWidth:    '<?php echo $min_width; ?>',   // minimum width of sub-menus in em units 
					maxWidth:    '<?php echo $max_width; ?>',   // maximum width of sub-menus in em units 
					extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
				}).superfish( opts ).find('ul').bgIframe({opacity:false}); 
			
			});
			
			</script>
		
			<?php
			
			}		
		}
		
		}
	}

} // class shailan_SFWidget