<?php
/*
Plugin Name: JC changes to edit page
Description: xxx
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}


// remove the old box
function remove_default_categories_box() {
	global $current_user;
	if (!current_user_can('superadmin')) {
    	remove_meta_box('categorydiv', 'post', 'side');
	}
}
add_action( 'admin_head', 'remove_default_categories_box' );

// add the new box
function add_custom_categories_box() {
	global $current_user;
	if (!current_user_can('superadmin')) {
   		add_meta_box('customcategorydiv', 'Categories', 'custom_post_categories_meta_box', 'post', 'side', 'low', array( 'taxonomy' => 'category' ));
	}
}
add_action('admin_menu', 'add_custom_categories_box');

/**
 * Display CUSTOM post categories form fields.
 *
 * @since 2.6.0
 *
 * @param object $post
 */
function custom_post_categories_meta_box( $post, $box ) {
	global $current_user;
    $defaults = array('taxonomy' => 'category');
    if ( !isset($box['args']) || !is_array($box['args']) )
        $args = array();
    else
        $args = $box['args'];
    extract( wp_parse_args($args, $defaults), EXTR_SKIP );
    $tax = get_taxonomy($taxonomy);
    ?>
    <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
        <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
            <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
            <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used' ); ?></a></li>
        </ul>

        <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
            <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
                <?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
            </ul>
        </div>

        <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
            <?php
            $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
            ?>
            <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
                <?php 
                /**
                 * This is the one line we had to change in the original function
                 * Notice that "checked_ontop" is now set to FALSE
                 */
                wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'popular_cats' => $popular_ids, 'checked_ontop' => FALSE ) ) ?>
            </ul>
        </div>
    <?php if ( !current_user_can($tax->cap->assign_terms) ) : ?>
    <p><em><?php _e('You cannot modify this taxonomy.'); ?></em></p>
    <?php endif; ?>
    <?php if ( current_user_can($tax->cap->edit_terms) ) : ?>

			<p><a href="<?php echo get_site_url(); ?>/wp-admin/request-category.php">Request new category</a></p>
			
        <?php endif; ?>
    </div>
    <?php
}

?>