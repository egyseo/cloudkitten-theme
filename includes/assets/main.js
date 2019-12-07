jQuery(function () {

	init_back_to_top();

	init_sticky_portfolio_menu();

	init_faux_animated_gif();

	// Enable mobile nav menu
	init_mobile_button('.mobile-menu-button', '#mobile-nav', '.mobile-menu', 'mobile_nav_open');

	// Enables ajax pagination on archive and search results pages
	//init_ajax_pagination();
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

		var $html = jQuery('html');

		var show_back_to_top = function () {
			var pageH = $html.outerHeight(true);
			var scrollH = $html[0].scrollHeight;
			if ( scrollH > pageH ) {
				$backToTop.show();
			} else {
				$backToTop.hide();
			}
		};

		// on page load and resize, check if this should be visible
		show_back_to_top();
		jQuery(window).smartresize(show_back_to_top);
	}

}

function init_sticky_portfolio_menu() {

	var $pageAnchors = jQuery("#page-anchors");
	if ( $pageAnchors.length ) {

		// VARIABLE DEFINITIONS

		var pastAnchors;
		var anchorFixed = false;
		var topOfPageAnchors = updateTopOfPageAnchors();
		var $adminBar = jQuery("#wpadminbar");
		var adminBar = jQuery("body").hasClass("admin-bar");

		var $pageAnchorLinks = $pageAnchors.find("a");
		var $pageAnchorDestinations = $pageAnchorLinks.map(function () {
			return jQuery(jQuery(this).attr("href"));
		}).get();

		var viewportHeight = jQuery(window).height();
		var pageAnchorPositions = [];
		jQuery.each($pageAnchorDestinations, function () {
			pageAnchorPositions.push(jQuery(this).offset().top - viewportHeight / 4);
		});

		// adds/removes a body class to disable everything on small screens
		var pageAnchorsEnabled = true;

		function updateBodyClass() {
			if ( jQuery(window).width() < 700 ) {
				pageAnchorsEnabled = false;
				jQuery("body").addClass("hide-page-anchors");
			} else {
				pageAnchorsEnabled = true;
				jQuery("body").removeClass("hide-page-anchors");
			}
		}

		// adds or removes "fixed" class from pageAnchors
		// set to true for minor performance benefit when scrolling
		function updateAnchorClass( ignoreAnchorFixed ) {

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

		}

		function updateAnchorClassOnScroll() {
			// don't do anything if the anchors aren't displaying
			if ( pageAnchorsEnabled ) {
				updateAnchorClass(false);
			}
		}

		function updateTopOfPageAnchors() {
			// don't use the page anchor offset directly because it won't work when they're fixed
			// subtracting 50 accounts for the margins and really shouldn't be hardcoded but here it is
			return $pageAnchors.next().offset().top - $pageAnchors.height() - 50;
		}


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
			topOfPageAnchors = updateTopOfPageAnchors();

			viewportHeight = jQuery(window).height();
			pageAnchorPositions = [];
			jQuery.each($pageAnchorDestinations, function () {
				pageAnchorPositions.push(jQuery(this).offset().top - viewportHeight / 4);
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


function init_mobile_button( button_selector, navigation_selector, inner_nav_selector, body_class ) {
	if ( typeof button_selector === 'undefined' || typeof navigation_selector === 'undefined' || !button_selector || !navigation_selector ) return;

	var $nav = jQuery(navigation_selector);
	var $button = jQuery(button_selector);

	if ( $button.length < 1 || $nav.length < 1 ) {
		return;
	}

	var $html = jQuery('html');
	var $body = jQuery('body');
	var $inner = $nav.find(inner_nav_selector);

	// button click toggles nav
	$button.click(function () {
		// Close any open submenus
		$nav.find('li.sub-menu-open').removeClass('sub-menu-open');

		if ( $body.hasClass(body_class) ) {
			// reset scroll position of .site-container while it's fixed in place
			$html.add($body).scrollTop(0);
		} else {
			var pageH = $html.outerHeight(true);
			var scrollH = $html[0].scrollHeight;

			if ( scrollH > pageH ) {
				// if the page had a scrollbar before menu opened, keep it whether or not menu requires it
				$body.addClass('require_scrollbar');
			} else {
				$body.removeClass('require_scrollbar');
			}
		}

		// Toggle the mobile nav menu
		$body.toggleClass(body_class);

		return false;
	});

	// clicking outside of the menu closes it
	$nav.click(function ( e ) {
		if ( e.target !== $inner[0] && !$inner.find(e.target).length ) {
			$button.trigger("click");
			return false;
		}
	});

	// pressing esc closes it
	jQuery(document).keyup(function ( e ) {
		if ( e.key === "Escape" && $body.hasClass(body_class) ) {
			$button.trigger("click");
			return false;
		}
	});


	$nav.find(".menu-item-has-children").append('<div class="menu-dropdown-toggle"></div>');
	$nav.on('click', '.menu-dropdown-toggle', function () {
		var $link = jQuery(this);
		var $item = $link.parent('li.menu-item');
		var $submenu = $item.children('ul.sub-menu:first');

		if ( $submenu.length > 0 ) {
			// Collapse sibling menus if they are open, as well as their children.
			$item.siblings('li.menu-item.sub-menu-open').each(function () {
				jQuery(this).removeClass('sub-menu-open');
				jQuery(this).find('li.menu-item.sub-menu-open').removeClass('sub-menu-open');
			});

			// Collapse or expand the clicked menu as needed.
			$item.toggleClass('sub-menu-open');

			return false;
		}
	});
}

function init_ajax_pagination() {

	if ( !jQuery(".loop-pagination").length ) return;

	var $main = jQuery("#main");

	// save current page as state
	history.replaceState({
		pageTitle: document.title,
		pageContent: $main.html()
	}, '', window.location.pathname + window.location.search);

	// update results on click of pagination links
	jQuery(document).on("click", ".loop-pagination a", function ( e ) {

		e.preventDefault();
		$main.addClass('ajax-loading');
		var urlToLoad = jQuery(this).attr("href");

		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: urlToLoad,
			data: { ajax: 1 }
		})
			.done(function ( json ) {
				$main
					.html(json.content)
					.removeClass('ajax-loading');
				document.title = json.title;
				if ( $main.offset().top < jQuery(window).scrollTop() ) {
					// top of $main is hidden above top of window; scroll up
					jQuery('html, body').animate({ scrollTop: $main.offset().top }, 200);
				}
				history.pushState({
					pageTitle: json.title,
					pageContent: json.content
				}, '', urlToLoad);
			})
			.fail(function () {
				window.location.href = urlToLoad;
			});
	});

	// update results on history change
	window.onpopstate = function ( e ) {
		$main.html(e.state.pageContent);
		document.title = e.state.pageTitle;
	};

}

