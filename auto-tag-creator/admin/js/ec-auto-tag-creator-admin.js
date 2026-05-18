function auto_tag_save_settings() {
	
	var setting_from = jQuery('form#tag_setting_from').serialize();
	
	jQuery.ajax({
		type: "POST",
		url: ajaxurl,
		data:'action=tag_save_settings&security='+ec_auto_tag_creator.security+'&'+setting_from,
		beforeSend: function() {
			jQuery("#submit_settings").prop('disabled', true);
			jQuery(".success_message").hide("slow");			
			jQuery(".loader_image").show("slow");			
		},
		success: function(data){
			jQuery(".loader_image").hide("slow");
			if(data.success) {
				jQuery("#tag_setting_from").before('<div class="success_message notice notice-success"><p><strong>'+data.msg+'</strong></p></div>');
				jQuery("#submit_settings").prop('disabled', false);
				setTimeout(function() { jQuery(".success_message").hide("slow"); }, 3000);
			}
		}
	});
	return false;
}
