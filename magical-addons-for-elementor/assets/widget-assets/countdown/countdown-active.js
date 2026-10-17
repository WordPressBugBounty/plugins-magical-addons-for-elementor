(function ($) {
	"use strict";

	function parseTargetDate(dateStr) {
		if (!dateStr) {
			return null;
		}
		var str = String(dateStr).trim();
		// Replace space with T for ISO compatibility
		var isoStr = str.replace(' ', 'T');
		if (isoStr.split(':').length === 2) {
			isoStr += ':00';
		}
		var d = new Date(isoStr);
		if (!isNaN(d.getTime())) {
			return d;
		}
		// Replace dashes with slashes for Safari compatibility
		d = new Date(str.replace(/-/g, '/'));
		if (!isNaN(d.getTime())) {
			return d;
		}
		// Regex parse "YYYY-MM-DD HH:mm:ss"
		var parts = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?/);
		if (parts) {
			return new Date(
				parseInt(parts[1], 10),
				parseInt(parts[2], 10) - 1,
				parseInt(parts[3], 10),
				parseInt(parts[4] || 0, 10),
				parseInt(parts[5] || 0, 10),
				parseInt(parts[6] || 0, 10)
			);
		}
		return null;
	}

	function initCountdownWidget(scope) {
		var clockElement = $(scope).find(".mga-clock");
		if (!clockElement.length) {
			return;
		}

		// Clear previous instance to support re-rendering in Elementor editor
		clockElement.empty();

		var displayType = clockElement.data('display-type') || 'timec';
		var clockFormat = clockElement.data('clock-format') || '12';

		if ('clock' === displayType) {
			var clockTimeFormate = ('12' == clockFormat) ? "TwelveHourClock" : "TwentyFourHourClock";
			clockElement.FlipClock({
				clockFace: clockTimeFormate
			});
		} else if ('timec' === displayType) {
			var now = new Date();
			var targetTime = clockElement.data('target-time');
			var targetDateObject = parseTargetDate(targetTime);

			// Automatically default to 1 year in the future if missing, invalid, or old 2024 date
			if (!targetDateObject || isNaN(targetDateObject.getTime()) || String(targetTime).indexOf('2024-') === 0 || targetDateObject.getTime() <= now.getTime()) {
				if (!targetDateObject || isNaN(targetDateObject.getTime()) || String(targetTime).indexOf('2024-') === 0) {
					targetDateObject = new Date(now.getTime() + (365 * 24 * 60 * 60 * 1000));
				}
			}

			var difference = Math.floor((targetDateObject.getTime() - now.getTime()) / 1000);
			if (difference < 0) {
				difference = 0;
			}
			var clockFace = (difference > 24 * 60 * 60) ? 'DailyCounter' : 'HourlyCounter';

			var clock = clockElement.FlipClock(difference, {
				clockFace: clockFace,
				countdown: true
			});

			var getdayLabel = clockElement.data('days-label');
			if (getdayLabel && String(getdayLabel).trim() !== '') {
				clockElement.find(".days span.flip-clock-label, .flip-clock-divider.days .flip-clock-label").text(getdayLabel);
			}

			var getHoursLabel = clockElement.data('hours-label');
			if (getHoursLabel && String(getHoursLabel).trim() !== '') {
				clockElement.find(".hours span.flip-clock-label, .flip-clock-divider.hours .flip-clock-label").text(getHoursLabel);
			}

			var getMunuteLabel = clockElement.data('minutes-label');
			if (getMunuteLabel && String(getMunuteLabel).trim() !== '') {
				clockElement.find(".minutes span.flip-clock-label, .flip-clock-divider.minutes .flip-clock-label").text(getMunuteLabel);
			}

			var getSecondLabel = clockElement.data('seconds-label');
			if (getSecondLabel && String(getSecondLabel).trim() !== '') {
				clockElement.find(".seconds span.flip-clock-label, .flip-clock-divider.seconds .flip-clock-label").text(getSecondLabel);
			}
		} else {
			var countdownValue = parseInt(clockElement.data('countdown'), 10) || 500;
			var timing = parseInt(clockElement.data('timing'), 10) || 1000;
			var clock = clockElement.FlipClock(countdownValue, {
				clockFace: 'Counter'
			});
			var timer = setInterval(function () {
				if (clock.getTime && clock.getTime().time <= 0) {
					clearInterval(timer);
				} else {
					clock.decrement();
				}
			}, timing);
		}
	}

	var initHandler = function () {
		elementorFrontend.hooks.addAction("frontend/element_ready/mgcountdown_widget.default", function (scope) {
			initCountdownWidget(scope);
		});
	};

	if (window.elementorFrontend && elementorFrontend.hooks) {
		initHandler();
	} else {
		$(window).on("elementor/frontend/init", initHandler);
	}

}(jQuery));
