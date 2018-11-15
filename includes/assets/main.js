jQuery(function () {

	// meh
	//init_back_to_top();

	init_sticky_portfolio_menu();

	init_faux_animated_gif();

});

function init_faux_animated_gif() {
	var $showOnHover = jQuery("#show-on-hover");
	if ( $showOnHover.length ) {
		var loaded = false;
		$showOnHover.parent().parent().hover(function () {
			if ( !loaded ) {
				// first time through, set the background image and start animating after load
				$showOnHover
					.css("background", "200px 0 url(" + $showOnHover.data("src") + ")")
					.imagesLoaded({ background: true }, function () {
						loaded = true;
						$showOnHover.css("visibility", "visible").animatr('play');
					});
			} else {
				$showOnHover.animatr('play');
			}
		}, function () {
			$showOnHover.animatr('stop');
		});
	}
}

function init_back_to_top() {
	var $backToTop = jQuery("#back-to-top");
	if ( $backToTop.length ) {
		$backToTop.click(function ( e ) {
			e.preventDefault();
			scroll_to_position({ scrollTop: 0 });
		});
	}
}

function init_sticky_portfolio_menu() {

	var $pageAnchors = jQuery("#page-anchors");
	if ( $pageAnchors.length ) {

		// VARIABLE DEFINITIONS

		var pastAnchors;
		var anchorFixed = false;
		var topOfPageAnchors = $pageAnchors.next().offset().top - $pageAnchors.height() - 15;
		var $adminBar = jQuery("#wpadminbar");
		var adminBar = jQuery("body").hasClass("admin-bar");

		var $pageAnchorLinks = $pageAnchors.find("a");
		var $pageAnchorDestinations = $pageAnchorLinks.map(function () {
			return jQuery(jQuery(this).attr("href"));
		}).get();
		var pageAnchorPositions = [];
		jQuery.each($pageAnchorDestinations, function () {
			pageAnchorPositions.push(jQuery(this).offset().top);
		});

		// adds/removes a body class to disable everything on small screens
		var pageAnchorsEnabled = true;
		var updateBodyClass = function () {
			if ( jQuery(window).width() < 700 ) {
				pageAnchorsEnabled = false;
				jQuery("body").addClass("hide-page-anchors");
				console.log("hiding anchors");
			} else {
				jQuery("body").removeClass("hide-page-anchors");
				console.log("unhiding anchors");
			}
		};

		// adds or removes "fixed" class from pageAnchors
		// set to true for minor performance benefit when scrolling
		var updateAnchorClass = function ( ignoreAnchorFixed ) {

			var pos = jQuery(window).scrollTop();

			if ( adminBar && $adminBar.css("position") === "fixed" ) {
				// admin bar is not fixed on narrow screens so we don't need to adjust for it
				pos = pos + $adminBar.height();
			}

			// on scroll event, save time by only updating anchor class when it's changing
			// on resize event, we don't know if we're changing the anchor class, so skip this test
			pastAnchors = pos > topOfPageAnchors;
			if ( (ignoreAnchorFixed || !anchorFixed) && pastAnchors ) {
				$pageAnchors.addClass('fixed');
				anchorFixed = true;
			} else if ( (ignoreAnchorFixed || anchorFixed) && !pastAnchors ) {
				$pageAnchors.removeClass('fixed');
				anchorFixed = false;
			}

			var i = 0;
			pos = pos + 100;
			for ( var total = pageAnchorPositions.length; i < total; i++ ) {
				if ( pos >= pageAnchorPositions[i] ) {
					if ( i + 1 === total || pos < pageAnchorPositions[i + 1] ) {
						$pageAnchorLinks.removeClass("active");
						$pageAnchorLinks.eq(i).addClass("active");
						break;
					}
				}
				$pageAnchorLinks.removeClass("active");
			}

		};

		var updateAnchorClassOnScroll = function () {
			// don't do anything if the anchors aren't displaying
			if ( pageAnchorsEnabled ) {
				updateAnchorClass(false);
			}
		};


		// EVENT HANDLERS

		// scroll to section when clicked
		$pageAnchorLinks.click(function ( e ) {
			e.preventDefault();
			$pageAnchorLinks.removeClass("active");
			jQuery(this).addClass('active');

			var goalPosition = jQuery(jQuery(this).attr("href")).offset().top;
			var adjust = $pageAnchors.height();
			if ( adminBar ) {
				adjust += $adminBar.height();
			}


			// unbind scroll event while scrolling
			jQuery(window).off("scroll", updateAnchorClassOnScroll);
			scroll_to_position({
				scrollTop: goalPosition - adjust,
				callback: function () {
					updateAnchorClass(true);
					jQuery(window).on("scroll", updateAnchorClassOnScroll);
				}
			});
		});


		// update anchor on scroll
		jQuery(window).on("scroll", updateAnchorClassOnScroll);

		// update anchor on resize
		jQuery(window).smartresize(function () {
			updateBodyClass();

			topOfPageAnchors = $pageAnchors.next().offset().top - $pageAnchors.height() - 15;

			pageAnchorPositions = [];
			jQuery.each($pageAnchorDestinations, function () {
				pageAnchorPositions.push(jQuery(this).offset().top);
			});

			updateAnchorClass(true);
		});


		// DO ON INIT

		updateBodyClass();
		updateAnchorClass(true);

	}
}


function scroll_to_position( options ) {

	var settings = jQuery.extend({
		scrollTop: 0,
		callback: function () {
		}
	}, options);

	jQuery("html, body").animate({
		scrollTop: settings.scrollTop
	}, 'slow', 'swing', function () {
		settings.callback.call(this);
	});

}


(function ( $, sr ) {

	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function ( func, threshold, execAsap ) {
		var timeout;

		return function debounced() {
			var obj = this, args = arguments;

			function delayed() {
				if ( !execAsap ) func.apply(obj, args);
				timeout = null;
			}

			if ( timeout ) clearTimeout(timeout); else if ( execAsap ) func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 100);
		};
	};
	// smartresize
	jQuery.fn[sr] = function ( fn ) {
		return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
	};

})(jQuery, 'smartresize');


/*
* modified from:
* filmstrip-animatr 0.9 @author Patrick Best
* https://github.com/varffs/jq-filmstrip-animatr
*/
(function ( $ ) {
	var spinin;

	function animate( opts, t ) {
		opts.bgpos = opts.bgpos + opts.width;
		t.css('background-position', opts.bgpos + 'px');
	}

	$.fn.animatr = function ( arg, options ) {
		var t = this;
		var bgpos = parseInt(this.css('background-position'), 10);
		if ( arg === 'stop' ) {
			clearInterval(spinin);
			spinin = 0;
			// reset background, on general principle
			t.css('background-position', (bgpos % 3200) + 'px');
		} else if ( arg === 'play' || arg === undefined ) {
			var opts = $.extend({}, $.fn.animatr.defaults, options);
			var frames = t.data('frames');
			opts = $.extend({
				'frames': frames,
				'bgpos': bgpos
			}, opts);
			spinin = window.setInterval(function () {
				animate(opts, t);
			}, 1000 / opts.fps);
		}
	};
	$.fn.animatr.defaults = {
		fps: 13,
		width: 200
	};
})(jQuery);

