//Function to create an iframe to hold our embedding on the front page, allowing us to make them present without the WPForms changing the way it looks
function ziggeowpformsCreateIframeEmbedding(element_id, embedding_tag, parameters_code) {

	if(typeof ziggeowpformsGetIframeHeaderCode === 'undefined') {
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
	//code += '<script>$.noConflict();</script>';
	code += ziggeowpformsGetIframeHeaderCode();

	code += '<script>' + 
				'var ZiggeoWP = ' + JSON.stringify(ZiggeoWP) + ';' +
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

			var embedding_element = _iframe.contentDocument.getElementById('embedding');

			if(embedding_tag === 'ziggeorecorder') {

				//Add embedding code
				var embedding = ZiggeoApi.V2.Recorder.findByElement(embedding_element);

				//Add verified code
				embedding.on("verified", function() {
					//pass the token out of the iframe
					placeholder.previousElementSibling.value = '[ziggeoplayer]' + 
																	embedding.get("video") +
																	'[/ziggeoplayer]'

					var tags = embedding_element.getAttribute('data-wpf-custom-tags');

					if(tags) {

						var _tags = [];
						tags = tags.split(',');

						for(i = 0, c = tags.length; i < c; i++) {
							try {
								var value = out_doc.getElementById(tags[i]).value;

								if(value.trim() !== '') {
									_tags.push(value);
								}
							}
							catch(err) {
								console.log(err);
							}
						}

						if(_tags.length > 0) {

							if(embedding.get('tags') !== '' && embedding.get('tags') !== null) {
								_tags.concat(embedding.get('tags'));
							}

							//Create tags for the video
							ZiggeoApi.Videos.update(embedding.get("video"), {
								tags: _tags
							});
						}

					}
				});
			}
			else if(embedding_tag === 'ziggeoplayer') {

				var embedding = ZiggeoApi.V2.Player.findByElement(embedding_element);

				embedding.on('ended', function() {
					placeholder.previousElementSibling.value = 'seen';
				});
			}
		}, 2000);
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