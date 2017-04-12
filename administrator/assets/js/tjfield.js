jQuery(document).ready(function(){

	// Added By Komal M
	jQuery(document).on("keyup",".charcounter",function() {
		var divTextarea = jQuery( this ).attr('id');
		jQuery( ".charcounter" ).each(function( index ) {

			counter_span = "#counter_" +divTextarea;
			char_count = jQuery( '#'+divTextarea ).val().length;
			jQuery(counter_span).text(char_count);
		});
	});

	/*Required fields valiadtion*/
	document.formvalidator.setHandler('min100', function(value, element) {
		value = value.trim();
		if (value.trim().length < 100)
		{
			return false;
		}
		return true;
	});

	document.formvalidator.setHandler('min200', function(value, element) {
		value = value.trim();
		if (value.trim().length < 200)
		{
			return false;
		}
		return true;
	});

	document.formvalidator.setHandler('min250', function(value, element) {
		value = value.trim();
		if (value.trim().length < 250)
		{
			return false;
		}
		return true;
	});

	document.formvalidator.setHandler('min300', function(value, element) {
		value = value.trim();
		if (value.trim().length < 300)
		{
			return false;
		}
		return true;
	});

	document.formvalidator.setHandler('blank-space', function(value, element) {
		if (value.trim() == '')
		{
			return false;
		}
		return true;
	});
	document.formvalidator.setHandler('numeric', function(value, element) {
		if(Number(value) <= 0)
		{
			return false;
		}
		return true;
	});
	document.formvalidator.setHandler('filesize', function(value, element) {
		var file_accept = element[0].accept;
		var accept_array = file_accept.split(",");
		var file_type = element[0].files[0].type;
		var afterDot = '.' + file_type.split("/").pop();
		
		var count = accept_array.indexOf(afterDot); 

		if(element[0].files[0].size > 15728640)
		{
			return false;
		}
		else if(count < 0)
		{
			return false;
		}
		return true;
	});
	document.formvalidator.setHandler('url', function(value, element) 
	{
		regex=/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&#\/%?=~_|!:,.;]*[-a-z0-9+&#\/%=~_|]/i;
		return regex.test(value);
	});

//------------------------------------------------------------------------------------------------------------------------------------------------
	jQuery(".btn-delete").click(function(){
		var field_id               = jQuery(this).attr("id");
		var fileName         = jQuery("#fileName_"+field_id).val();

		var commentContainer = jQuery(this).parent().parent().parent().parent();

		if (confirm("Are you sure to remove the uploaded document?") == false)
		{
			return false;
		}

		jQuery.ajax({
			url:'?option=com_tjucm&task=itemform.delete_doc',
			type: 'post',
			data:{field_id:field_id, fileName:fileName},
			dataType : 'json',
			success : function(resp){
				if (resp.error)
				{
					jQuery("#message").html(resp.message);
					jQuery("#message").addClass('alert alert-danger');
				}
				else
				{
					jQuery("#uploadedreceipt_"+field_id).hide();
				}
			},
			error : function(resp){
			}
		});
	});
	// END
});
