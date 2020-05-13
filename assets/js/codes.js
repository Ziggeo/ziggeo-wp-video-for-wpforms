//Function to create an iframe to hold our embedding on the front page, allowing us to make them present without the WPForms changing the way it looks
function createIframeEmbedding(element_id, embedding_tag, parameters_code) {

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
	else {
		code += '<' + embedding_tag + ' id="embedding" ' + parameters_code + '></' + embedding_tag + '>';
	}

	//Get all elements that need to get the iframe
	var placeholder = document.getElementById(element_id);

	//If the element is not yet available, lets try soon instead of continuing.
	if(typeof placeholder === 'undefined' || placeholder === null) {
		setTimeout(function(){
			createIframeEmbedding(element_id, embedding_tag, parameters_code)
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

	iframe.addEventListener('load', function(e) {
		//remove the borders
		var _iframe = this;

		ziggeowpforms_iframe_noscroll(_iframe);

		setTimeout(function() {

			//Set the height one more time
			ziggeowpforms_iframe_noscroll(_iframe);

			if(embedding_tag === 'ziggeorecorder') {

				//Add embedding code
				var embedding = ZiggeoApi.V2.Recorder.findByElement(
					_iframe.contentDocument.getElementById('embedding')
				);

				//Add verified code
				embedding.on("verified", function() {
					//pass the token out of the iframe
					placeholder.previousElementSibling.value = '[ziggeoplayer]' + 
																	embedding.get("video") +
																	'[/ziggeoplayer]'
				});
			}
			else if(embedding_tag === 'ziggeoplayer') {

				var embedding = ZiggeoApi.V2.Player.findByElement(
					_iframe.contentDocument.getElementById('embedding')
				);

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
	iframe_body.style.overflowX = 'hidden';
	iframe_body.style.overflowY = 'hidden';
}