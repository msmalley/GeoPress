var gmaps_settings = {
	threshold    : 0,
	container    : window
};

jQuery.belowthefold = function(element, settings) {
	var fold = jQuery(window).height() + jQuery(window).scrollTop();
	return fold <= jQuery(element).offset().top - settings.threshold;
};

jQuery.rightoffold = function(element, settings) {
	var fold = jQuery(window).width() + jQuery(window).scrollLeft();
	return fold <= jQuery(element).offset().left - settings.threshold;
};
	
jQuery.abovethetop = function(element, settings) {
	var fold = jQuery(window).scrollTop();
	return fold >= jQuery(element).offset().top + settings.threshold  + jQuery(element).height();
};

jQuery.leftofbegin = function(element, settings) {
	var fold = jQuery(window).scrollLeft();
	return fold >= jQuery(element).offset().left + settings.threshold + jQuery(element).width();
};

var alreadyInit = new Array()
function array_find(array,item){
	for(var i=0;i<array.length;i++){
		if(item == array[i])
			return true;
	}	
	return false;
}

function checkGmapsContainer(){
	jQuery('div.tppo_mapCanvas').each(function(){
		if (jQuery.abovethetop(this, gmaps_settings) ||
			jQuery.leftofbegin(this, gmaps_settings)) {
				/* Nothing. */
		} else if (!jQuery.belowthefold(this, gmaps_settings) &&
			!jQuery.rightoffold(this, gmaps_settings)) {
				if(!array_find(alreadyInit,this)){
					alreadyInit.push(this);
					var id = jQuery(this).attr('id').replace('mapCanvas_','');
					eval('initialize_'+id+'();');
				}
		} else {
			return false;
		}
	});
}

jQuery(document).ready(function(){
	setInterval(checkGmapsContainer,1000);								
});