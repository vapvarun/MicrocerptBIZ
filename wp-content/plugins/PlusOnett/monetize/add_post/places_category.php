<?php
$cat_display=get_option('ptthemes_category_dislay');
if($cat_display==''){$cat_display='select';}
global $wpdb;
$cat_display=get_option('ptthemes_category_dislay');
if($cat_display=='select'){
$cat_args = array('name' => 'category[]', 'id' => 'category_0', 'selected' => $cat_array, 'class' => 'textfield', 'orderby' => 'name', 'echo' => '1', 'hierarchical' => 1, 'taxonomy'=>CUSTOM_CATEGORY_TYPE1);
$cat_args['show_option_none'] = __('Select Category','templatic');
wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
}else
{
	$catsql = "select c.term_id, c.name from $wpdb->terms c,$wpdb->term_taxonomy tt  where tt.term_id=c.term_id and tt.taxonomy='".CUSTOM_CATEGORY_TYPE1."' order by c.name";
	$catinfo = $wpdb->get_results($catsql);
	global $cat_array;
	if($catinfo)
	{
		$counter = 0;
		foreach($catinfo as $catinfo_obj)
		{
			$counter++;
			$termid = $catinfo_obj->term_id;
			$name = $catinfo_obj->name;
			$cat_display='checkbox';
			if($cat_display=='checkbox'){
			?>
			<div class="form_cat" ><label><input type="checkbox" name="category[]" id="category_<?php echo $counter;?>" value="<?php echo $termid; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($termid,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $name; ?></label></div>
			<?php
			}elseif($cat_display=='radio')
			{
			?>
			<div class="form_cat" ><label><input type="radio" name="category[]" id="category_<?php echo $counter;?>" value="<?php echo $termid; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($termid,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $name; ?></label></div>
			<?php
			}
		}
	}	
}
?>