//
// INDEX
//********
// 1. Iframe functions
//		* ziggeowpformsCreateIframeEmbedding()
//		* ziggeowpforms_iframe_noscroll()
// 2. Ziggeo Hooks
//		* ziggeowpformsSaveToken()
//		* ziggeowpformsAddCustomTags()
//		* ziggeowpformsAddCustomData()


/////////////////////////////////////////////////
// 1. IFRAME FUNCTIONS                         //
/////////////////////////////////////////////////


	//Function to create an iframe to hold our embedding on the front page, allowing us to make them present without the WPForms changing the way it looks
	function ziggeowpformsCreateIframeEmbedding(element_id, embedding_tag, parameters_code) {

		if(typeof ziggeowpformsGetIframeHeaderCode === 'undefined' || typeof ZiggeoWP === 'undefined') {
			window.addEventListener('load', function() {
				return ziggeowpformsCreateIframeEmbedding(element_id, embedding_tag, parameters_code);
			});

			return;
		}

		//Create dynamic iframe
		var iframe = document.createElement('iframe');

		//1. Grab the WP Ziggeo codes
		//var resources_info = ziggeo_p_assets_prepare_raw(); (PHP ONLY - skip for now to do a proper POC)

		var code = '<script src="' + ZiggeoWP.url_jquery + '"></script>';

		code += '<script>var ZiggeoWP = parent.ZiggeoWP;</script>';

		code += ziggeowpformsGetIframeHeaderCode();

		code += '<script>' +
					'var ziggeo_app = new ZiggeoApi.V2.Application(' + JSON.stringify(ziggeoGetApplicationOptions()) + ');' +
				'</script>';

		//We should create the code at this point.
		// 2. embedding code
		if(embedding_tag === 'ziggeovideowall') {
			//create video wall
			code += ziggeoRestoreTextValues(parameters_code, [{'from': '&lt;', 'to': '<'}, {'from': '&gt;', 'to': '>'}]);

			//jQuery is not within the iframe, so this helps
			code = code.replace( new RegExp('jQuery(document).ready('.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), "g"), 'window.addEventListener("load",' );

		}
		else if(embedding_tag === 'ziggeotemplate') {
			code += parameters_code;
		}
		else {
			code += '<' + embedding_tag + ' id="embedding" ' + parameters_code + '></' + embedding_tag + '>';
		}

		//Get all elements that need to get the iframe
		var placeholder = document.getElementById(element_id);

		//If the element is not yet available, lets try soon instead of continuing.
		if(typeof placeholder === 'undefined' || placeholder === null) {
			setTimeout(function(){
				ziggeowpformsCreateIframeEmbedding(element_id, embedding_tag, parameters_code)
				return true;
			}, 2000);

			return false;
		}

		//Just to be safe, let us clear this out first
		placeholder.innerHTML = '';

		placeholder.appendChild(iframe);

		iframe.contentWindow.document.open('text/html', 'replace');
		iframe.contentWindow.document.write(code);
		iframe.contentWindow.document.close();

		var out_doc = document;

		iframe.addEventListener('load', function(e) {
			//remove the borders
			var _iframe = this;

			ziggeowpforms_iframe_noscroll(_iframe);

			setTimeout(function() {

				//Set the height one more time
				ziggeowpforms_iframe_noscroll(_iframe);

				var embedding = _iframe.contentDocument.getElementById('embedding');

				if(embedding_tag === 'ziggeorecorder') {

					//Add embedding code
					var embedding_object = ZiggeoApi.V2.Recorder.findByElement(embedding);

					//Add verified code
					embedding_object.on("verified", function() {

						//Get the element to save the data to (it is outside of iframe in WPForms)
						var element = placeholder.previousElementSibling;
						var value_prepared = '';

						if(ZiggeoWP && ZiggeoWP.wpforms) {
							//Capture the video token
							value_prepared = ZiggeoWP.wpforms.capture_format.replace('{token}', embedding_object.get("video"));
						}
						else {
							//Capture the video token
							value_prepared = "[ziggeoplayer]" + embedding_object.get("video") + "[/ziggeoplayer]"
						}

						window.ZiggeoWP.hooks.fire('ziggeowpforms_verified', {
							'embedding_element': embedding,
							'embedding_object': embedding_object,
							'value_prepared': value_prepared,
							'save_to_element': element
						});

					});
				}
				else if(embedding_tag === 'ziggeoplayer') {

					var embedding_object = ZiggeoApi.V2.Player.findByElement(embedding);

					embedding_object.on('ended', function() {
						placeholder.previousElementSibling.value = 'seen';
					});
				}
			}, 2000);

			window.ziggeowpformsSaveToken();
			window.ziggeowpformsAddCustomTags();
			window.ziggeowpformsAddCustomData();
		});
	}

	//Remove scrollbars from the iframe
	function ziggeowpforms_iframe_noscroll(iframe_element) {

		//this way it works for IE as well
		var iframe_document = iframe_element.contentDocument || iframe_element.contentWindow.document;
		var iframe_body = iframe_document.querySelector("body");

		//adding a bit more height as pixel perfect can bite
		iframe_element.style.height = iframe_body.scrollHeight + 10 + 'px';
		iframe_element.style.width = '100%';
		iframe_body.style.overflowX = 'hidden';
		iframe_body.style.overflowY = 'hidden';
	}




/////////////////////////////////////////////////
// 2. ZIGGEO HOOKS                             //
/////////////////////////////////////////////////

	//Helper to set the hook to capture and save the video token.
	function ziggeowpformsSaveToken() {
		ZiggeoWP.hooks.set('ziggeowpforms_verified', 'ziggeowpformsSaveToken',
			function(data) {
				//Save the video token
				data.save_to_element.value = data.value_prepared;
			});
	}

	//Handling save of custom (dynamic) tags
	function ziggeowpformsAddCustomTags() {
		ZiggeoWP.hooks.set('ziggeowpforms_verified', 'ziggeowpformsAddCustomTags',
			function(data) {
				//Get tags
				var tags = data.embedding_element.getAttribute('data-wpf-custom-tags');

				if(tags) {
					var _tags = [];
					tags = tags.split(',');

					for(i = 0, c = tags.length; i < c; i++) {
						try {
							var value = document.getElementById(tags[i]);

							if(value) {
								value = value.value.trim();
							}

							if(value.trim() !== '') {
								_tags.push(value);
							}
						}
						catch(err) {
							console.log(err);
						}
					}

					if(_tags.length > 0) {

						if(data.embedding_object.get('tags') !== '' && data.embedding_object.get('tags') !== null) {
							_tags.concat(data.embedding_object.get('tags'));
						}

						//Create tags for the video
						ziggeo_app.videos.update(data.embedding_object.get("video"), { tags: _tags });
					}
				}
			});
	}

	//Handling save of dynamic custom data
	function ziggeowpformsAddCustomData() {
		ZiggeoWP.hooks.set('ziggeowpforms_verified', 'ziggeowpformsAddCustomData',
			function(data) {
				//Get custom data
				var c_data = data.embedding_element.getAttribute('data-custom-data');
				//Example: first_name:wpforms-66-field_2,last_name:wpforms-66-field_3

				if(c_data) {
					var prepared_data = {};
					var _found = false;

					c_data = c_data.split(',');
					//Example: Array [ "first_name:wpforms-66-field_2", "last_name:wpforms-66-field_3" ]

					for(i = 0, c = c_data.length; i < c; i++) {
						try {

							var _temp = c_data[i].split(':');
							//Example: "Array [ "first_name", "wpforms-66-field_2" ]"

							var value = document.getElementById(_temp[1]);

							if(value) {
								value = value.value.trim();
							}
							else {
								value = '';
							}

							prepared_data[_temp[0]] = value;
							_found = true;
						}
						catch(err) {
							console.log(err);
						}
					}

					if(_found === true) {

						//We do not want to touch custom data that was there previosuly, so we either use one or the other.

						//Create tags for the video
						ziggeo_app.videos.update(data.embedding_object.get("video"), { data: prepared_data });
					}
				}
			});
	}
