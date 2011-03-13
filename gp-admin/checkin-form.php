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
function gpjs_get_lingo_transport(checkin_mode, checkin_location) {
        if(checkin_location == '— WHICH PLACE...? —') {
            var bridge = '';
            checkin_location = 'nowhere';
        }else{
            var bridge = 'to ';
        }
	if(checkin_mode == 'teleport') {
		checkin_title = 'I teleported '+bridge+checkin_location;
	}else if(checkin_mode == 'drive') {
		checkin_title = 'I drove '+bridge+checkin_location;
	}else if(checkin_mode == 'ride') {
		checkin_title = 'I rode '+bridge+checkin_location;
	}else if(checkin_mode == 'walk') {
		checkin_title = 'I walked '+bridge+checkin_location;
	}else if(checkin_mode == 'run') {
		checkin_title = 'I ran '+bridge+checkin_location;
	}else if(checkin_mode == 'fly') {
		checkin_title = 'I flew '+bridge+checkin_location;
	}else{
		checkin_title = 'I am '+bridge+checkin_location;
	}
	return checkin_title;
}
jQuery(document).ready(function() {
    var modeValue = jQuery('#dd_mode select option:selected').val();
    var placeValue = jQuery('#dd_places select option:selected').text();
    var newName = gpjs_get_lingo_transport(modeValue, placeValue)
    jQuery('#dd_name input').val(newName);
    /* REPEAT PROCESS FOR CHANGING SELECTS */
    jQuery("select").change(function() {
      var modeValue = jQuery('#dd_mode select option:selected').val();
      var placeValue = jQuery('#dd_places select option:selected').text();
      var id = jQuery(this).attr('id');
      var value = jQuery(this).val();
      if(id == 'checkin[mode]') {
        modeValue = jQuery(this).find('option:selected').val();
        var newName = gpjs_get_lingo_transport(modeValue, placeValue)
        jQuery('#dd_name input').val(newName);
        jQuery('#dd_slug input').val("");
      }
      if(id == 'checkin[placeid]') {
        placeValue = jQuery(this).find('option:selected').text();
        var newName = gpjs_get_lingo_transport(modeValue, placeValue)
        jQuery('#dd_name input').val(newName);
        jQuery('#dd_slug input').val("");
      }
    });
});
</script>

<?php

$place_id = (int)$_GET['place_id'];
if(empty($place_id)){
    $place_id = $checkin->placeid;
}

?>

<dl>

	<div style="float:left; width:75%; padding-top:15px;" class="main-content">
        
        <div style="float:left; width:49%; padding-right:1%" class="half-half">
        
            <div class="meta-box-title"><?php _e('Checkin Type'); ?></div>
            <div class="meta-box details">
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gp_meta_sidebar_table">
                  <tr>
                    <td width="50%">
                        
                        <dt><label for="checkin[mode]"><?php _e('Mode');  ?></label></dt>
                        <dd id="dd_mode"><?php echo gp_transport_dropdown('', $checkin->mode, 'checkin'); ?></dd>
                    
                    </td>
                    <td width="10px" class="divider">&nbsp;</td>
                    <td width="50%">
                        
                        <dt><label for="checkin[placeid]"><?php _e('Place');  ?></label></dt>
                        <dd id="dd_places"><?php echo gp_places_dropdown( 'checkin[placeid]', $place_id, false, __('-- WHICH PLACE...? --')); ?></dd>
                    
                    </td>
                  </tr>
                  <tr>
                    <td width="50%">
                        
                        <dt><label for="checkin[announcementtype]"><?php _e('Announcement Type');  ?></label></dt>
                        <dd><?php echo gp_announcement_dropdown('', $checkin->announcementtype, 'checkin'); ?></dd>
                    
                    </td>
                    <td width="10px" class="divider">&nbsp;</td>
                    <td width="50%">
                    
                    </td>
                  </tr>
                </table>
                
                <?php
                    $placeid = $checkin->placeid;
                    $mode = $checkin->mode;
                    $place = GP::$place->by_id($placeid);
                    $placename = $place->name;
                    $checkin_title = gp_get_lingo_transport($mode, $placename);
                ?>

                    <dd id="dd_name" style="display:none;"><input type="hidden" name="checkin[name]" value="<?php echo $checkin_title; ?>" id="checkin[name]"></dd>
                    <dd id="dd_slug" style="display:none;"><input type="hidden" name="checkin[slug]" value="<?php if(!empty($checkin->slug)) { echo $checkin->slug; } ?>" id="checkin[slug]"></dd>
                    <input type="hidden" name="checkin[latlng]" value="<?php echo $checkin->latlng; ?>" id="checkin[latlng]">
                
                <p class="submit" style="margin-top:-55px; border-top:none;">
                    <?php
                    $current_url = gp_url_current();
                    $root_url = gp_url_base_root();
                    $new_checkin = $root_url.'checkins/-new';
                    if($current_url == $new_checkin) {
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
            <div class="meta-box-title"><h2><?php _e('Announcement'); ?> <a href="javascript:toggleEditor('checkin[announcement]');" class="action edit">[ <?php _e('Toggle WYSIWYG'); ?> ]</a> </h2></div>
            <div class="meta-box description">
            	<dd><div class="input_wrapper"><textarea name="checkin[announcement]" class="gp_description" rows="4" cols="40" id="checkin[announcement]"><?php echo esc_html( $checkin->announcement ); ?></textarea></div></dd>
            </div>
            
        </div>

        <div style="clear:both; float:left; width:100%; margin-top:-10px;" class="full-half"></div>
        
    </div>
        
    <div style="float:left; width:23%; padding-left:2%; padding-top:15px;" class="sidebar">
        
        <div style="clear:both; float:left; width:100%;" class="full-half">
       
            <div class="meta-box-title"><?php _e('Checkin Meta'); ?></div>
            <div class="meta-box location">
            
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gp_meta_sidebar_table">
                  <tr>
                    <td width="50%">
                        
                        <dt><label for="checkin[mapid]"><?php _e('Map');  ?></label></dt>
                        <dd><?php echo gp_maps_dropdown( 'checkin[mapid]', $checkin->mapid, false, '-- SELECT MAP --', 1); ?></dd>
                    
                    </td>
                    <td width="10px" class="divider">&nbsp;</td>
                    <td width="50%">
                        
                        <dt><label for="checkin[parent_checkin_id]"><?php _e('Parent Checkin');  ?></label></dt>
                        <dd><?php echo gp_checkins_dropdown( 'checkin[parent_checkin_id]', $checkin->parent_checkin_id); ?></dd>
                    
                    </td>
                  </tr>
                </table>

                <!-- HIDDEN META -->
                <?php
                if(empty($checkin->checkin_time)){
                    $checkin_time = time();
                    //gp_printr($place_created);
                }else{
                    $checkin_time = $checkin->checkin_time;
                } ?>
                <input type="hidden" id="checkin[checkin_time]" name="checkin[checkin_time]" value="<?php echo $checkin_time; ?>" />
            
            </div> 
       
        </div>
        
    </div>
	
</dl>

<?php echo gp_js_focus_on( 'checkin[mode]' ); ?>
