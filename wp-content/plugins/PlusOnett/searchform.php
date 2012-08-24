<div class="search">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>">
        <fieldset>
            <input name="s" type="text" onfocus="if(this.value=='<?php _e('Search','templatic');?>') this.value='';" onblur="if(this.value=='') this.value='<?php _e('Search','templatic');?>';" value="<?php _e('Search','templatic');?>" />
            <button type="submit"></button>
        </fieldset>
    </form>
</div>