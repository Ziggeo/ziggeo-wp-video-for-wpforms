/*
	This file holds the codes that are used on backed / in wp-admin
*/

window.addEventListener('load', function() {
	if(typeof wpf !== 'undefined') {
		// Register us for the changes notification
		//inputs
		jQuery(document).on('focusout', '.wpforms-field-option-row input.ziggeowpforms-player-option', wpf.fieldUpdate);
		jQuery(document).on('focusout', '.wpforms-field-option-row input.ziggeowpforms-recorder-option', wpf.fieldUpdate);
		//dropdowns/selects
		jQuery(document).on('change', '.wpforms-field-option-row select.ziggeowpforms-player-option', wpf.fieldUpdate);
		jQuery(document).on('change', '.wpforms-field-option-row select.ziggeowpforms-recorder-option', wpf.fieldUpdate);
		//jQuery(document).on('focusout', '.wpforms-field-option-row .ziggeowpforms-walls-option', wpf.fieldUpdate);
	}
});


//get the update from the change
jQuery(document).on('wpformsFieldUpdate', function(e, fields) {
	//get the actual object of interest
	for(var field in fields) {
		if(fields.hasOwnProperty(field)) {
			//Should we handle it?
			if(fields[field].type === 'video-player') {
				ziggeoWPFormsPlayerUpdate(fields[field]);
			}
			else if(fields[field].type === 'video-recorder') {
				ziggeoWPFormsRecorderUpdate(fields[field]);
			}
		}
	}
});

//Adds the token to embedding once it is added within the settings
function ziggeoWPFormsPlayerUpdate(info) {

	if(typeof ZiggeoApi === 'undefined') {
		setTimeout(function() {
			ziggeoWPFormsPlayerUpdate(info);
		}, 200);
		return false;
	}

	//get the embedding
	var embedding = ZiggeoApi.V2.Player.findByElement(
		document.getElementById( 'wpforms-field-' + info.id ).getElementsByTagName('ziggeoplayer')[0]
	);

	//The update will happen as soon as you click the button to add the field as well..
	if(embedding) {

		// Standard
		//*********

		//Apply video token if not already
		if(info.video_token !== '') {
			embedding.set('video', info.video_token);
		}

		// Styles
		//*******

		if(info.width !== '') {
			embedding.set('width', info.width.replace('px', ''));
		}
		if(info.height !== '') {
			embedding.set('height', info.height.replace('px', ''));
		}

		//popup
		if(info.popup) {
			embedding.set('popup', true);
		}
		else {
			embedding.set('popup', false);
		}

		if(info.popup_width !== '') {
			embedding.set('popup-width', info.popup_width);
		}
		if(info.popup_height !== '') {
			embedding.set('popup-height', info.popup_height);
		}

		//theme
		embedding.set('theme', info.theme);
		embedding.set('themecolor', info.theme_color);

		// Advanced
		//*********

		if(info.client_auth !== '') {
			embedding.set('client-auth', info.client_auth);
		}
		if(info.server_auth !== '') {
			embedding.set('server-auth', info.server_auth);
		}
		if(info.effect_profiles !== '') {
			embedding.set('effect-profiles', info.effect_profiles);
		}
		if(info.video_profile !== '') {
			embedding.set('video-profiles', info.video_profile);
		}

		//This makes the player look right
		embedding.host.next("Initial");
	}
}

function ziggeoWPFormsRecorderUpdate(info) {

	if(typeof ZiggeoApi === 'undefined') {
		setTimeout(function() {
			ziggeoWPFormsRecorderUpdate(info);
		}, 200);
		return false;
	}

	//get the embedding
	var embedding = ZiggeoApi.V2.Recorder.findByElement(
		document.getElementById( 'wpforms-field-' + info.id ).getElementsByTagName('ziggeorecorder')[0]
	);

	//The update will happen as soon as you click the button to add the field as well..
	if(embedding) {

		// Standard
		//*********


		// Styles
		//*******

		if(info.width !== '') {
			embedding.set('width', info.width.replace('px', ''));
		}
		if(info.height !== '') {
			embedding.set('height', info.height.replace('px', ''));
		}

		//popup
		if(info.popup) {
			embedding.set('popup', true);
		}
		else {
			embedding.set('popup', false);
		}

		if(info.popup_width !== '') {
			embedding.set('popup-width', info.popup_width);
		}
		if(info.popup_height !== '') {
			embedding.set('popup-height', info.popup_height);
		}

		//theme
		embedding.set('theme', info.theme);
		embedding.set('themecolor', info.theme_color);

		// Advanced
		//*********

		if(info.client_auth !== '') {
			embedding.set('client-auth', info.client_auth);
		}
		if(info.server_auth !== '') {
			embedding.set('server-auth', info.server_auth);
		}
		if(info.effect_profiles !== '') {
			embedding.set('effect-profiles', info.effect_profiles);
		}
		if(info.video_profile !== '') {
			embedding.set('video-profiles', info.video_profile);
		}

		//This makes the player look right
		embedding.host.next("Initial");
	}
}