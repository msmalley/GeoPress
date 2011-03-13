
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
        
            <div class="meta-box-title"><?php _e('Map Details'); ?></div>
            
            <div class="meta-box details">
                <dt><label for="map[name]"><?php _e('Name');  ?></label></dt>
                <dd><div class="input_wrapper ui-corner-all-5"><input type="text" name="map[name]" value="<?php echo esc_html( $map->name ); ?>" id="map[name]"></div></dd>
                <!-- TODO: make slug edit WordPress style -->
                <dt><label for="map[slug]"><?php _e('Slug');  ?></label><small><?php _e('If you leave the slug empty, it will be derived from the name and ID.'); ?></small></dt>
                <dd>
                    <div class="input_wrapper ui-corner-all-5"><input type="text" name="map[slug]" value="<?php echo esc_html( $map->slug ); ?>" id="map[slug]"></div>
                </dd>	    
                
                <p class="submit">
					<?php
                    $current_url = gp_url_current();
                    $root_url = gp_url_base_root();
                    $new_map = $root_url.'maps/-new';
                    if($current_url == $new_map) {
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
                    <span class="or-cancel right"><a href="javascript:history.back();">Cancel</a></span>
                </p>
        
            </div>
        
        </div>
        
        <div style="float:left; width:48%; padding-left:2%" class="half-half">
        
            <!-- DESCRIPTION -->
            <div class="meta-box-title"><h2><?php _e('Map Description'); ?> <a href="javascript:toggleEditor('map[description]');" class="action edit">[ <?php _e('Toggle WYSIWYG'); ?> ]</a> </h2></div>
            <div class="meta-box description">
                <dd><div class="input_wrapper"><textarea name="map[description]" class="gp_description" rows="4" cols="40" id="map[description]"><?php echo esc_html( $map->description ); ?></textarea></div></dd>
            </div>
        
        </div>
        
        <div style="clear:both; float:left; width:100%; margin-top:-10px;" class="full-half">
       
            <!-- LOCATION -->
            <div class="meta-box-title"><?php _e('Location'); ?></div>
            <div class="meta-box location">
                <dd style="padding:15px 0 0 0">
                	<?php geopress_geoform($map); ?>
                </dd>
            </div> 
       
        </div>
    
    </div>
    
    <div style="float:left; width:23%; padding-left:2%; padding-top:15px;" class="sidebar">
    
		<div class="meta-box-title"><?php _e('Map Options'); ?></div>
        <div class="meta-box options">
        
            <?php
			$this_map_type = esc_html( $map->type );
			$this_map_zoom = esc_html( $map->zoom );
			?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gp_meta_sidebar_table">
              <tr>
                <td width="50%">
                    <label><?php echo _e('Map Type:'); ?></label>
                    <input type="radio" name="map[type]" value="ROADMAP" <?php if(($this_map_type == 'ROADMAP') || ($this_map_type == '')) { ?> checked="checked" <?php } ?> /><span class="input_label first">Roadmap</span><br />
                    <input type="radio" name="map[type]" value="SATELLITE" <?php if($this_map_type == 'SATELLITE') { ?> checked="checked" <?php } ?> /><span class="input_label">Satellite</span><br />
                    <input type="radio" name="map[type]" value="HYBRID" <?php if($this_map_type == 'HYBRID') { ?> checked="checked" <?php } ?> /><span class="input_label">Hybrid</span><br />
                    <input type="radio" name="map[type]" value="TERRAIN" <?php if($this_map_type == 'TERRAIN') { ?> checked="checked" <?php } ?> /><span class="input_label">Terrain</span><br />
                </td>
                <td width="10px" class="divider">&nbsp;</td>
                <td width="50%">
                    <label><?php echo _e('Zoom Level:'); ?></label>
                    <input type="radio" name="map[zoom]" value="18" <?php if($this_map_zoom == '18') { ?> checked="checked" <?php } ?> /><span class="input_label first">Close-Up</span><br />
                    <input type="radio" name="map[zoom]" value="13" <?php if(($this_map_zoom == '13') || ($this_map_zoom == '' )) { ?> checked="checked" <?php } ?> /><span class="input_label">Nearby</span><br />
                    <input type="radio" name="map[zoom]" value="10" <?php if($this_map_zoom == '10') { ?> checked="checked" <?php } ?> /><span class="input_label">Cities</span><br />
                    <input type="radio" name="map[zoom]" value="5" <?php if($this_map_zoom == '5') { ?> checked="checked" <?php } ?> /><span class="input_label">Countries</span><br />
                </td>
              </tr>
            </table>
              
            <!-- PRIVATE -->          
            <dt style="clear:both; display:block; border-top:1px dotted #CCC; padding:16px 0 15px;">
            	<label for="map[private]"><?php _e('Private...?'); ?></label>
                <input type="checkbox" id="map[private]" name="map[private]" <?php gp_checked( $map->private ); ?> />
            </dt>
        
        </div>
        
        <div class="meta-box-title"><?php _e('Advanced Options'); ?></div>
        <div class="meta-box advance">
        
            <!-- DISPLAY TYPE -->
            <dt><label for="map[display_type]"><?php _e('Display Type');  ?></label></dt>
            <dd style="padding:0 0 20px; margin:0 0 20px; border-bottom:1px dotted #CCC;"><?php echo gp_select( 'map[display_type]', array('places'=>__('Show Places'),'checkins'=>__('Show Checkins')), $map->display_type ); ?></dd>

            <!-- PARENT MAP -->
            <dt><label for="map[parent_map_id]"><?php _e('Parent Map');  ?></label></dt>
            <dd style="padding:0 0 20px; margin:0 0 20px; border-bottom:1px dotted #CCC;"><?php echo gp_maps_dropdown( 'map[parent_map_id]', $map->parent_map_id); ?></dd>
            
            <!-- HEIGHT --> 
			<?php 
			$this_map_height = $map->height;
			if(empty($this_map_height)) {
				$this_map_height = 450;
			}
			?>
            <dt><label for="map[height]"><?php _e('Height');  ?></label></dt>
            <dd>
                <div class="input_wrapper ui-corner-all-5"><input type="text" name="map[height]" value="<?php echo esc_html( $this_map_height ); ?>" id="map[height]"></div>
                <small><?php _e('Default is 450.'); ?></small>
            </dd>
            
            <!-- NUMBER OF MARKER -->
			<?php 
			$this_map_markers = $map->numberofmarkers;
			if(empty($this_map_markers)) {
				$this_map_markers = 10;
			}
			?>
            <dt><label for="map[numberofmarkers]"><?php _e('Number of Markers');  ?></label></dt>
            <dd>
                <div class="input_wrapper ui-corner-all-5"><input type="text" name="map[numberofmarkers]" value="<?php echo esc_html( $this_map_markers ); ?>" id="map[numberofmarkers]"></div>
                <small><?php _e('Default is 10.'); ?></small>
            </dd>

            <!-- HIDDEN META -->
            <?php
            if(empty($map->created)){
                $map_created = time();
                //gp_printr($map_created);
            }else{
                $map_created = $map->created;
            }$map_updated = time();
            ?>
            <input type="hidden" id="map[created]" name="map[created]" value="<?php echo $map_created; ?>" />
            <input type="hidden" id="map[updated]" name="map[updated]" value="<?php echo $map_updated; ?>" />
        
        </div>
    
    </div>
    
</dl>

<?php echo gp_js_focus_on( 'map[name]' ); ?>
