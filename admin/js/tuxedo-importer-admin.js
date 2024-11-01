(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	tuxedoImporterUpdateCheckbox();
	jQuery(".show_url input").change(function () {
		tuxedoImporterUpdateCheckbox();
	});
	/*jQuery(".channel_item .channel_checkbox").change(function () {
		tuxedoImporterToggleChannelUrl(jQuery(this).parent());
	});*/
	function tuxedoImporterUpdateCheckbox() {
		if (jQuery(".show_url input").prop('checked')) {
			jQuery(".singles_checkbox").show();
		} else {
			jQuery(".singles_checkbox").hide();
		}
	}
	/*function tuxedoImporterToggleChannelUrl(item) {
        console.log('Item', item)
		if (item.find('.channel_checkbox').prop('checked')) {
            console.log('Checked');
			item.find('.channel_url').css('display', 'block');
		} else {
            console.log('Not checked');
			item.find('.channel_url').css('display', 'none');
		}
	}*/
})(jQuery);
