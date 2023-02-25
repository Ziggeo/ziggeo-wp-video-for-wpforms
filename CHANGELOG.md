This file contains the change log info for the `Ziggeo Video For WPForms` (WPForms bridge) plugin.


=======


= 1.10 =
* Fixed: Additional sizing CSS has been added to the plugin, making sure that the smaller devices can show the recorder properly. Thank you Gaben for reporting.

= 1.9 =
* Fixed: Starting with WPForms 1.6.8.1 the options were not readable due to how the menu is built. We have added CSS that is loaded only on admin side to help with this builder issue.
* Fixed: Added additional check for the values when creating fields because for some fields that was possibility of seeing some errors in the form builder (admin side only)
* Improvement: Added additional CSS to make the embedding shown properly on smaller screens. Also added additional code for iframe sizing so that it does not go off running with dynamic and hard cap on.
* Fixed: Added codes that should be persent on iframe to help with various security limitations put on iframes by default

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
