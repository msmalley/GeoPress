<script type="text/javascript">
tinyMCE.init({
    mode : "textareas",
    theme : "advanced",
    theme_advanced_buttons1 : "mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
    theme_advanced_buttons2 : "",
    theme_advanced_buttons3 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    plugins : 'inlinepopups',
    setup : function(ed) {
        // Display an alert onclick
		/*
        ed.onClick.add(function(ed) {
            ed.windowManager.alert('User clicked the editor.');
        });

        // Add a custom button
        ed.addButton('mybutton', {
            title : 'My button',
            image : 'img/example.gif',
            onclick : function() {
                ed.selection.setContent('<strong>Hello world!</strong>');
            }
        });
		*/
    }
});
function toggleEditor(id) {
	if (!tinyMCE.get(id))
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
}
</script>

<dl>

	<div style="float:left; width:75%; padding-top:15px;" class="main-content">
        
        <div style="float:left; width:49%; padding-right:1%" class="half-half">
        
            <div class="meta-box-title"><?php _e('Place Details'); ?></div>
            <div class="meta-box details">
            
                <dt><label for="place[name]"><?php _e('Name');  ?></label></dt>
                <dd><div class="input_wrapper ui-corner-all-5"><input type="text" name="place[name]" value="<?php echo esc_html( $place->name ); ?>" id="place[name]"></div></dd>
                
                <!-- TODO: make slug edit WordPress style -->
                <dt><label for="place[slug]"><?php _e('Slug');  ?></label><small><?php _e('If you leave the slug empty, it will be derived from the name and ID.'); ?></small></dt>
                <dd>
                    <div class="input_wrapper ui-corner-all-5"><input type="text" name="place[slug]" value="<?php echo esc_html( $place->slug ); ?>" id="place[slug]"></div>
                </dd>	
                
                <p class="submit">
                    <?php
                    $current_url = gp_url_current();
                    $root_url = gp_url_base_root();
                    $new_place = $root_url.'places/-new';
                    if($current_url == $new_place) {
                        $current_action = 'new';
                    }else{
                        $current_action = 'edit';
                    }
                    if($current_action == 'new') {
                        $button_text = esc_attr( __('Create') );					
                    }else{
                        $button_text = esc_attr( __('Save') );
                    }
                    ?>
                    <input type="submit" name="submit" value="<?php echo $button_text; ?>" id="submit" class="right" />
                    <span class="or-cancel right">or <a href="javascript:history.back();">Cancel</a></span>
                </p>
                
            </div>
        
        </div>
        
        <div style="float:left; width:48%; padding-left:2%" class="half-half">
        
            <!-- DESCRIPTION -->
            <div class="meta-box-title"><h2><?php _e('Place Description'); ?> <a href="javascript:toggleEditor('place[description]');" class="action edit">[ <?php _e('Toggle WYSIWYG'); ?> ]</a> </h2></div>
            <div class="meta-box description">
                <dd><div class="input_wrapper"><textarea name="place[description]" class="gp_description" rows="4" cols="40" id="place[description]"><?php echo esc_html( $place->description ); ?></textarea></div></dd>
            </div>
        
        </div>

        <div style="clear:both; float:left; width:100%; margin-top:-10px;" class="full-half">
       
            <!-- LOCATION -->
            <div class="meta-box-title"><?php _e('Location'); ?></div>
            <div class="meta-box location">
                <dd style="padding:15px 0 0 0">
                	<?php geopress_geoform($place, 'place'); ?>
                </dd>
            </div> 
       
        </div>
        
    </div>
    
    <div style="float:left; width:23%; padding-left:2%; padding-top:15px;" class="sidebar">
        
        <div style="clear:both; float:left; width:100%;" class="full-half">
       
            <!-- META -->
            <div class="meta-box-title"><?php _e('Place Meta'); ?></div>
            <div class="meta-box location">
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gp_meta_sidebar_table">
                  <tr>
                    <td width="50%">
                
                        <dt><label for="place[mapid]"><?php _e('Map');  ?></label></dt>
                        <dd><?php echo gp_maps_dropdown('place[mapid]', $place->mapid, false, '-- SELECT MAP --', 1); ?></dd>
                    
                    </td>
                    <td width="10px" class="divider">&nbsp;</td>
                    <td width="50%">
                
                        <dt><label for="place[parent_place_id]"><?php _e('Parent Place');  ?></label></dt>
                        <dd><?php echo gp_places_dropdown( 'place[parent_place_id]', $place->parent_place_id); ?></dd>
                    
                    </td>
                  </tr>
                </table>

                <!-- PRIVATE -->
                <dt style="clear:both; display:block; border-top:1px dotted #CCC; padding:16px 0 15px;">
                    <label for="place[private]"><?php _e('Private...?'); ?></label>
                    <input type="checkbox" id="place[private]" name="place[private]" <?php gp_checked( $place->private ); ?> />
                </dt>

                <!-- HIDDEN META -->
                <?php
                if(empty($place->created)){
                    $place_created = time();
                    //gp_printr($place_created);
                }else{
                    $place_created = $place->created;
                }$place_updated = time();
                ?>
                <input type="hidden" id="place[created]" name="place[created]" value="<?php echo $place_created; ?>" />
                <input type="hidden" id="place[updated]" name="place[updated]" value="<?php echo $place_updated; ?>" />
            
            </div>
       
        </div>
        
    </div>
	
</dl>

<?php echo gp_js_focus_on( 'place[name]' ); ?>