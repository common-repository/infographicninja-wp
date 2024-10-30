jQuery(document).ready(function($) {
    $('#generate_infographic_button').on('click', function() {
        var post_id = infographicninja_ajax_object.post_id;
		 var nonce = infographicninja_ajax_object.nonce;
		 
        // Show spinner
        showSpinner();

        // Ajax call to handle the Generate Infographic button click
        $.ajax({
            type: 'POST',
            url: infographicninja_ajax_object.ajax_url,
            data: {
                action: 'infographicninja_generate_infographic',
                post_id: post_id,
                security: nonce
            },
            success: function(response) {
                // Hide spinner
                hideSpinner();

                if (response.success) {
					//alert('what is repsonse: ' + response.data);	//post ID 
					insertImageIntoPost(response.data);                    
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function(error) {
                // Hide spinner
                hideSpinner();
                alert('Error: ' + error.responseText);
            }
        });
    });
    function showSpinner() {     

		// Create and append an overlay with a spinner
		var overlay = $('<div id="infographicninja_overlay"><img width="30px" src="images/spinner-2x.gif"></div>');
		$('body').append(overlay);
    }
	

    function hideSpinner() {
        // Remove the spinner element
        $('#infographicninja_overlay').remove();
    }
	
	function insertImageIntoPost(attachmentId) {
		   // Get the current post editor
		var editor = wp.data.select('core/editor');
		
		// Retrieve the image URL from the attachment ID
		wp.media.attachment(attachmentId).fetch().then(function(attachment) {
			//var imageUrl = attachment.attributes.url;
			// Convert the object to a JSON string
			var jsonString = JSON.stringify(attachment);
		//alert('what jsonString: ' + jsonString);
			// Parse the JSON string back to an object (optional, depending on your use case)
			var jsonData = JSON.parse(jsonString);

			// Accessing the URL from the JSON
			var imageUrl = jsonData.url;
			var imgcaption = jsonData.title;


			//alert('what imageUrl: ' + imageUrl);
			// Create a new Image block
			var block = wp.blocks.createBlock('core/image', {
				url: imageUrl,
				alt: imgcaption,
				caption: imgcaption,
				id: 'attachment_' + attachmentId,
				align: 'center',
				className: '',
			});

			// Insert the block into the editor
			wp.data.dispatch('core/editor').insertBlocks(block);

			alert('Infographic inserted successfully!');
		});
	}

});
