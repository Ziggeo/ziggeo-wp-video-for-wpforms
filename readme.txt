=== Ziggeo Video for WPForms ===
Contributors: oliverfriedmann, baned, carloscsz409, natashacalleia
Tags: ziggeo, video, video field, form builder, video form, WPForms
Requires at least: 3.0.1
Tested up to: 5.8
Stable tag: 1.9
Requires PHP: 5.2.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin brings video player and recorder (including screen recording) to your WPForms by utilizing the powerful and award winning Ziggeo for video.

Please note that you need to install and setup [Ziggeo plugin](https://wordpress.org/plugins/ziggeo/) first. This plugin is offered as an extension of the same.

== Who is this for? ==

Are you looking for a simple way to create your forms?
Want to add multimedia support to your forms?
Big fan of the future is today approach? - Video is that future and with this plugin you can do all of that today!

= Benefits =

Allows you to quickly add videos to your forms.
Simple Drag-and-drop integration of Ziggeo to your WPForms
Add player, recorder, screen recorder and more to your forms
Native integration, clean imlpementation and great support

== Screenshots ==


== Installation ==
 
1. Upload plugin zip file to your plugins directory. Usually that would be to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That is it or
1. Use the Plugins Add new section to find the plugin and install
 
== Frequently Asked Questions ==

= How does it work? =

This plugin will provide you with the Ziggeo Fields section in your form editor. Once you open it, it will reveal all of the different types of fields that we support.

By a simple drag and drop you can quickly add multimedia to your forms.

= Where does integration happen? =

Integration happens within your website. All the data you gather will still be available to you in same panels and integrations as before.

As always we will host multimedia that is captured within your Ziggeo account and link to the same will be used as a submitted value on your form.

= How to use Dynamic Custom Data =

Ziggeo internally supports the ability of adding custom data to your videos. This can be anything as long as it is provided as valid JSON field. Now with form builders you might want to add custom data based on the data in the fields as well. To do this, we bring you dynamic custom data field.

* Please note that this field should not be used in combination with the custom data. You should use either `Custom Data` or `Dynamic Custom Data`.

The way you would set it up is by using key:field_id. For example if you want your JSON to be formed as:

[javascript]
{
	"first_name": "Mike",
	"last_name": "Wazowski"
}
[/javascript]

and let's say that your first name has `<input id="wpforms-66-field_2" ...>` and last name has `<input id="wpforms-66-field_3" ...>`. It means that we need `wpforms-66-field_2` and `wpforms-66-field_3` to get those values. So our field can be set as:

`first_name:wpforms-66-field_2,last_name:wpforms-66-field_3`

As you save your recorder field it will remember this and try to find the values. If the fields with ID are not found, the value will be saved as "" (empty string)

= How can I get some support =

We provide active support to all that have any questions or need any assistance with our plugin or our service.
To submit your questions simply go to our [Help Center](https://support.ziggeo.com/hc/en-us). Alternatively just send us an email to [support@ziggeo.com](mailto:support@ziggeo.com).

= I have an idea or suggestion =

Please go to our [WordPress forum](https://support.ziggeo.com/hc/en-us/community/topics/200753347-WordPress-plugin) and add your suggestion within it. This allows everyone to see and vote on it and us to determine what should be next.

== Upgrade Notice ==

= 1.9 =
* Fixed: Starting with WPForms 1.6.8.1 the options were not readable due to how the menu is built. We have added CSS that is loaded only on admin side to help with this builder issue.
* Fixed: Added additional check for the values when creating fields because for some fields that was possibility of seeing some errors in the form builder (admin side only)
* Improvement: Added additional CSS to make the embedding shown properly on smaller screens. Also added additional code for iframe sizing so that it does not go off running with dynamic and hard cap on.
* Fixed: Added codes that should be persent on iframe to help with various security limitations put on iframes by default

== Changelog ==

= 1.8.1 =
* Fixed: The endless resizing was happening on Chrome. This resolves the same issue by doing double checks.

= 1.8 =
* Fixed: Added a change to iframe resizing to make it resized properly.

= 1.7 =
* Fixed: Videowalls now load properly within the wpforms. Depending on version of Videowalls plugin you might not have experienced any issues so far.
* Added: JS Hooks notification for video verified. Fires outside of iframe making it easy to hook to the same.
* Added: Support for Dynamic custom data

= 1.6 =
* Improvement: API is now using only V2 calls
* Improvement: Added a notification if core plugin is not installed instead of silently stopping the load.

= 1.5 =
* Removed unneeded file
* Fixed a typo which would result in popup not working as expected
* Added support for custom tags. Add the ID of the field (comma separated list accepted) and values from these fields will be used as additional tags.

= 1.4 =
* Fixed the parsing within the templates field where it could load to the error depending on how long the difference is in output of different sections of website. Now it will work right regardless of it.
* Added additional resilience in case anyone called the same function manually in their own plugin.
* Changed the function name from `createIframeEmbedding` to `ziggeowpformsCreateIframeEmbedding` to make sure there are no conflicts with third party plugins.

= 1.3 =
* Fixed a typo in the settings
* Changed the addon / integration code to use the new system

= 1.2. =
* The code output is changed to make it less likely to cause any issues

= 1.1. =
* Added small fix to fix the case where toSource was not available and causing issue.

= 1.0 =
Initial commit
