(function($){
	"use strict";

	jQuery(document).ready(function($) {
		// Temporary hide on cross button (X) click - visual only, reappears on page reload
		$(document).on('click', '.mg-sales-notice .notice-dismiss', function(e) {
			e.preventDefault();
			var $notice = $(this).closest('.mg-sales-notice');
			$notice.slideUp(250, function() {
				$notice.remove();
			});
			// Do NOT send AJAX dismissal request. It will show again on next page reload.
		});

		// 25-day persistent dismiss on clicking "Dismiss the notice" button
		$(document).on('click', '.mg-sales-notice .mg-sales-dismiss-btn', function(e) {
			e.preventDefault();
			var $notice = $(this).closest('.mg-sales-notice');
			$notice.slideUp(250, function() {
				$notice.remove();
			});

			if (typeof magicalAdminInfo !== 'undefined' && magicalAdminInfo.ajaxurl && magicalAdminInfo.sales_nonce) {
				$.ajax({
					url: magicalAdminInfo.ajaxurl,
					type: 'POST',
					data: {
						action: 'magical_dismiss_sales_notice',
						nonce: magicalAdminInfo.sales_nonce
					}
				});
			}
		});
	});

})(jQuery);