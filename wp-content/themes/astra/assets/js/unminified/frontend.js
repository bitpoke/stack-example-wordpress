/**
 * File fronend.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 *
 * @package Astra
 */

/**
 * Get all of an element's parent elements up the DOM tree
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var astraGetParents = function ( elem, selector ) {

	// Element.matches() polyfill.
	if ( ! Element.prototype.matches) {
		Element.prototype.matches =
			Element.prototype.matchesSelector ||
			Element.prototype.mozMatchesSelector ||
			Element.prototype.msMatchesSelector ||
			Element.prototype.oMatchesSelector ||
			Element.prototype.webkitMatchesSelector ||
			function(s) {
				var matches = (this.document || this.ownerDocument).querySelectorAll( s ),
					i = matches.length;
				while (--i >= 0 && matches.item( i ) !== this) {}
				return i > -1;
			};
	}

	// Setup parents array.
	var parents = [];

	// Get matching parent elements.
	for ( ; elem && elem !== document; elem = elem.parentNode ) {

		// Add matching parents to array.
		if ( selector ) {
			if ( elem.matches( selector ) ) {
				parents.push( elem );
			}
		} else {
			parents.push( elem );
		}
	}
	return parents;
};

/**
 * Deprecated: Get all of an element's parent elements up the DOM tree
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var getParents = function ( elem, selector ) {
	console.warn( 'getParents() function has been deprecated since version 2.5.0 or above of Astra Theme and will be removed in the future. Use astraGetParents() instead.' );
	astraGetParents( elem, selector );
}

/**
 * Toggle Class funtion
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var astraToggleClass = function ( el, className ) {
	if ( el.classList.contains( className ) ) {
		el.classList.remove( className );
	} else {
		el.classList.add( className );
	}
};

/**
 * Deprecated: Toggle Class funtion
 *
 * @param  {Node}   elem     The element.
 * @param  {String} selector Selector to match against [optional].
 * @return {Array}           The parent elements.
 */
var toggleClass = function ( el, className ) {
	console.warn( 'toggleClass() function has been deprecated since version 2.5.0 or above of Astra Theme and will be removed in the future. Use astraToggleClass() instead.' );
	astraToggleClass( el, className );
};

// CustomEvent() constructor functionality in Internet Explorer 9 and higher.
(function () {

	if (typeof window.CustomEvent === "function") return false;
	function CustomEvent(event, params) {
		params = params || { bubbles: false, cancelable: false, detail: undefined };
		var evt = document.createEvent('CustomEvent');
		evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
		return evt;
	}
	CustomEvent.prototype = window.Event.prototype;
	window.CustomEvent = CustomEvent;
})();

/**
 * Trigget custom JS Event.
 *
 * @since 1.4.6
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent
 * @param {Node} el Dom Node element on which the event is to be triggered.
 * @param {Node} typeArg A DOMString representing the name of the event.
 * @param {String} A CustomEventInit dictionary, having the following fields:
 *			"detail", optional and defaulting to null, of type any, that is an event-dependent value associated with the event.
 */
var astraTriggerEvent = function astraTriggerEvent( el, typeArg ) {
	var customEventInit =
	  arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

	var event = new CustomEvent(typeArg, customEventInit);
	el.dispatchEvent(event);
};

/**
 * Scroll to ID/Top with smooth scroll behavior.
 *
 * @since x.x.x
 *
 * @param {Event} e Event which is been fired.
 * @param {String} top offset from top.
 */
astraSmoothScroll = function astraSmoothScroll( e, top ) {
	e.preventDefault();
	window.scrollTo({
		top: top,
		left: 0,
		behavior: 'smooth'
	});
};

/**
 * Scroll to Top trigger visibility adjustments.
 *
 * @since x.x.x
 *
 * @param {Node} masthead Page header.
 * @param {Node} astScrollTop Scroll to Top selector.
 */
astScrollToTopHandler = function ( masthead, astScrollTop ) {

	var content = getComputedStyle(astScrollTop).content,
		device  = astScrollTop.dataset.onDevices;
	content = content.replace( /[^0-9]/g, '' );

	if( 'both' == device || ( 'desktop' == device && '769' == content ) || ( 'mobile' == device && '' == content ) ) {
		// Get current window / document scroll.
		var  scrollTop = window.pageYOffset || document.body.scrollTop;
		// If masthead found.
		if( masthead && masthead.length ) {
			if (scrollTop > masthead.offsetHeight + 100) {
				astScrollTop.style.display = "block";
			} else {
				astScrollTop.style.display = "none";
			}
		} else {
			// If there is no masthead set default start scroll
			if ( window.pageYOffset > 300 ) {
				astScrollTop.style.display = "block";
			} else {
				astScrollTop.style.display = "none";
			}
		}
	} else {
		astScrollTop.style.display = "none";
	}
};

( function() {

	var menu_toggle_all 	 = document.querySelectorAll( '#masthead .main-header-menu-toggle' ),
		main_header_masthead = document.getElementById('masthead'),
		menu_click_listeners_nav = {},
		mobileHeaderType = '',
		body = document.body,
		mobileHeader = '';

	if ( undefined !== main_header_masthead && null !== main_header_masthead ) {

		mobileHeader = main_header_masthead.querySelector("#ast-mobile-header");
	}

	if ( '' !== mobileHeader && null !== mobileHeader ) {

		mobileHeaderType = mobileHeader.dataset.type;
	}

	document.addEventListener( 'astMobileHeaderTypeChange', updateHeaderType, false );

	/**
	 * Updates the header type.
	 */
	function updateHeaderType( e ) {
		mobileHeaderType = e.detail.type;
		var popupTrigger = document.querySelectorAll( '.menu-toggle' );

		if( 'dropdown' === mobileHeaderType ) {

			document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
			updateTrigger('updateHeader');
		}

		if ( 'off-canvas' === mobileHeaderType ) {
			popupTrigger.forEach(function (trigger) {
				if (trigger && trigger.classList.contains('toggled')) {
					trigger.click();
				}
			});
		}
	
		init(mobileHeaderType);
	}
	
	function syncToggledClass() {
		const buttons = document.querySelectorAll('.menu-toggle');
			const allToggled = Array.from(buttons).every(button => button.classList.contains('toggled'));
	
		buttons.forEach(button => {
			if (allToggled) {
				button.classList.remove('toggled');
			} else {
				button.classList.add('toggled');
			}
		});
	}
	
	document.addEventListener('click', function (e) {
		const button = e.target.closest('.menu-toggle');
		if (button) {
			button.classList.toggle('toggled');
			syncToggledClass();
		}
	});
	

	/**
	 * Opens the Popup when trigger is clicked.
	 */
	popupTriggerClick = function ( event ) {

		var triggerType = event.currentTarget.trigger_type;
		var popupWrap = document.getElementById( 'ast-mobile-popup' );

		const menuToggleClose = document.getElementById('menu-toggle-close');

		if( menuToggleClose ) {
			menuToggleClose.focus();
		}

        if ( ! body.classList.contains( 'ast-popup-nav-open' ) ) {
			body.classList.add( 'ast-popup-nav-open' );
        }


		if ( ! body.classList.contains( 'ast-main-header-nav-open' ) && 'mobile' !== triggerType ) {
			body.classList.add( 'ast-main-header-nav-open' );
		}

		if ( ! document.documentElement.classList.contains( 'ast-off-canvas-active' ) ) {
			document.documentElement.classList.add( 'ast-off-canvas-active' );
		}

		if ( 'desktop' === triggerType ) {

			popupWrap.querySelector( '.ast-mobile-popup-content' ).style.display = 'none';
			popupWrap.querySelector( '.ast-desktop-popup-content' ).style.display = 'block';
		}
		if ( 'mobile' === triggerType ) {

			popupWrap.querySelector( '.ast-desktop-popup-content' ).style.display = 'none';
			popupWrap.querySelector( '.ast-mobile-popup-content' ).style.display = 'block';
		}
		if (event && event.currentTarget && event.currentTarget.style) {
			event.currentTarget.style.display = 'none';
		}

		popupWrap.classList.add( 'active', 'show' );
	}

	/**
	 * Closes the Trigger when Popup is Closed.
	 */
	function updateTrigger(currentElement) {
		mobileHeader = main_header_masthead.querySelector( "#ast-mobile-header" );
		var parent_li_sibling = '';

		if( undefined !== mobileHeader && null !== mobileHeader && 'dropdown' === mobileHeader.dataset.type && 'updateHeader' !== currentElement ) {
			return;
		}
		if ( undefined !== currentElement && 'updateHeader' !== currentElement ) {

			parent_li_sibling = currentElement.closest( '.ast-mobile-popup-inner' ).querySelectorAll('.menu-item-has-children');

		} else {
			var popup = document.querySelector( '#ast-mobile-popup' );
			parent_li_sibling = popup.querySelectorAll('.menu-item-has-children');

		}

		parent_li_sibling.forEach((li_sibling) => {
			li_sibling.classList.remove('ast-submenu-expanded');
		
			const all_sub_menu = Array.from(li_sibling.querySelectorAll('.sub-menu'));
			all_sub_menu.forEach((sub_menu) => {
				if (!sub_menu.hasAttribute('data-initial-display')) {
					sub_menu.setAttribute('data-initial-display', window.getComputedStyle(sub_menu).display);
				}
		
				if (sub_menu.getAttribute('data-initial-display') === 'block') {
					sub_menu.style.display = 'block';
				} else {
					sub_menu.style.display = 'none';
				}
			});
		});
		
        var popupTrigger = document.querySelectorAll( '.menu-toggle' );

		document.body.classList.remove( 'ast-main-header-nav-open', 'ast-popup-nav-open' );
		document.documentElement.classList.remove( 'ast-off-canvas-active' );

		for ( var item = 0;  item < popupTrigger.length; item++ ) {

			popupTrigger[item].classList.remove( 'toggled' );

			popupTrigger[item].style.display = 'flex';
		}
	}

	/**
	 * Main Init Function.
	 */
	function init( mobileHeaderType ) {

		var popupTriggerMobile = document.querySelectorAll( '#ast-mobile-header .menu-toggle' );
		var popupTriggerDesktop = document.querySelectorAll( '#ast-desktop-header .menu-toggle' );

		if ( undefined === mobileHeaderType && null !== main_header_masthead ) {

			mobileHeader = main_header_masthead.querySelector("#ast-mobile-header");
			if( mobileHeader ) {
				mobileHeaderType = mobileHeader.dataset.type;
			} else {
				var desktopHeader = main_header_masthead.querySelector("#ast-desktop-header");
				if ( desktopHeader ) {

					mobileHeaderType = desktopHeader.dataset.toggleType;
				} else {
					return;
				}
			}
		}

		if ( 'off-canvas' === mobileHeaderType ) {
			var popupClose = document.getElementById( 'menu-toggle-close' ),
				popupInner = document.querySelector( '.ast-mobile-popup-inner' );

			if ( undefined === popupInner || null === popupInner ) {
				return; // if toggel button component is not loaded.
			}
			popupLinks = popupInner.getElementsByTagName( 'a' );

			// --- Focus Trap Implementation Start ---
			document.removeEventListener( 'keydown', astraOffcanvasTrapTabKey );

			function astraOffcanvasTrapTabKey( e ) {
				let popup = document.getElementById( 'ast-mobile-popup' );
				if ( ! popup || ! popup.classList.contains( 'active' ) || e.key !== 'Tab' ) {
					return;
				}
				let focusableElements = popupInner.querySelectorAll(
					'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
				);
				focusableElements = Array.prototype.filter.call( focusableElements, function ( element ) {
					return (
						element.offsetWidth > 0 && element.offsetHeight > 0 && window.getComputedStyle( element ).visibility !== 'hidden'
					);
				} );
				if ( focusableElements.length === 0 ) return;
				let firstElement = focusableElements[ 0 ];
				let lastElement = focusableElements[ focusableElements.length - 1 ];
				if ( e.shiftKey && document.activeElement === firstElement ) {
					e.preventDefault();
					lastElement.focus();
				} else if ( ! e.shiftKey && document.activeElement === lastElement ) {
					e.preventDefault();
					firstElement.focus();
				}
			}
			document.addEventListener( 'keydown', astraOffcanvasTrapTabKey );

			// Remove focus trap when menu is closed
			function removeAstraOffcanvasTrap() {
				document.removeEventListener( 'keydown', astraOffcanvasTrapTabKey );
			}
			if ( popupClose ) {
				popupClose.addEventListener( 'click', removeAstraOffcanvasTrap );
			}
			document.addEventListener( 'keyup', function ( event ) {
				if ( event.keyCode === 27 ) {
					removeAstraOffcanvasTrap();
				}
			} );
			document.addEventListener( 'click', function ( event ) {
				let target = event.target;
				let modal = document.querySelector( '.ast-mobile-popup-drawer.active .ast-mobile-popup-overlay' );
				if ( target === modal ) {
					removeAstraOffcanvasTrap();
				}
			} );

			for ( var item = 0; item < popupTriggerMobile.length; item++ ) {
				popupTriggerMobile[ item ].removeEventListener( 'click', astraNavMenuToggle, false );
				// Open the Popup when click on trigger
				popupTriggerMobile[ item ].removeEventListener( 'click', popupTriggerClick );
				popupTriggerMobile[ item ].addEventListener( 'click', function(event) {
					popupTriggerClick(event);
					const menu = document.querySelector('.ast-mobile-popup-drawer.active');
					if (!menu) {
						removeAstraOffcanvasTrap();
					}
				}, false );
				popupTriggerMobile[ item ].trigger_type = 'mobile';
			}
			for ( var item = 0; item < popupTriggerDesktop.length; item++ ) {
				popupTriggerDesktop[ item ].removeEventListener( 'click', astraNavMenuToggle, false );
				// Open the Popup when click on trigger
				popupTriggerDesktop[ item ].removeEventListener( 'click', popupTriggerClick );
				popupTriggerDesktop[ item ].addEventListener( 'click', popupTriggerClick, false );
				popupTriggerDesktop[ item ].trigger_type = 'desktop';
			}

			// Getting menu toggle button element.
			const menuToggleButton = document.querySelector( '.ast-button-wrap .menu-toggle' );

			//Close Popup on CLose Button Click.
			popupClose.addEventListener( 'click', function ( e ) {
				document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
				updateTrigger( this );
				menuToggleButton?.focus();
			} );

			// Close Popup if esc is pressed.
			document.addEventListener( 'keyup', function ( event ) {
				// 27 is keymap for esc key.
				if ( event.keyCode === 27 ) {
					event.preventDefault();
					document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
					updateTrigger();
					menuToggleButton?.focus();
				}
			} );

			// Close Popup on outside click.
			document.addEventListener( 'click', function ( event ) {
				var target = event.target;
				var modal = document.querySelector( '.ast-mobile-popup-drawer.active .ast-mobile-popup-overlay' );
				if ( target === modal ) {
					document.getElementById( 'ast-mobile-popup' ).classList.remove( 'active', 'show' );
					updateTrigger();
					menuToggleButton?.focus();
				}
			} );

			// Close Popup on # link click inside Popup.
			for ( let link = 0, len = popupLinks.length; link < len; link++ ) {
				if (
					null !== popupLinks[ link ].getAttribute( 'href' ) &&
					( popupLinks[ link ].getAttribute( 'href' ).startsWith( '#' ) ||
						-1 !== popupLinks[ link ].getAttribute( 'href' ).search( '#' ) ) &&
					( ! popupLinks[ link ].parentElement.classList.contains( 'menu-item-has-children' ) ||
						( popupLinks[ link ].parentElement.classList.contains( 'menu-item-has-children' ) &&
							document.querySelector( 'header.site-header' ).classList.contains( 'ast-builder-menu-toggle-icon' ) ) )
				) {
					popupLinks[ link ].addEventListener( 'click', triggerToggleClose, true );
					popupLinks[ link ].headerType = 'off-canvas';
				}
			}

			AstraToggleSetup();
		} else if ( 'dropdown' === mobileHeaderType ) {

			var mobileDropdownContent = document.querySelectorAll( '.ast-mobile-header-content' ) || false,
				desktopDropdownContent = document.querySelector( '.ast-desktop-header-content' ) || false;

			// Close Popup on # link click inside Popup.
			if ( mobileDropdownContent.length > 0 ) {
				for ( let index = 0; index < mobileDropdownContent.length; index++ ) {
					var mobileLinks = mobileDropdownContent[ index ].getElementsByTagName( 'a' );
					for ( link = 0, len = mobileLinks.length; link < len; link++ ) {
						// Check if the link is not inside the tabs container
						const isNotInsideTabsContainer = mobileLinks[ link ].closest( '.wp-block-uagb-tabs' ) === null;

						if (
							null !== mobileLinks[ link ].getAttribute( 'href' ) &&
							( mobileLinks[ link ].getAttribute( 'href' ).startsWith( '#' ) ||
								-1 !== mobileLinks[ link ].getAttribute( 'href' ).search( '#' ) ) &&
							( ! mobileLinks[ link ].parentElement.classList.contains( 'menu-item-has-children' ) ||
								( mobileLinks[ link ].parentElement.classList.contains( 'menu-item-has-children' ) &&
									document.querySelector( 'header.site-header' ).classList.contains( 'ast-builder-menu-toggle-icon' ) ) ) &&
							isNotInsideTabsContainer
						) {
							mobileLinks[ link ].addEventListener( 'click', triggerToggleClose, true );
							mobileLinks[ link ].headerType = 'dropdown';
						}
					}
				}
			}

			// Close Popup on # link click inside Popup.
			if ( desktopDropdownContent ) {
				var desktopLinks = desktopDropdownContent.getElementsByTagName( 'a' );
				for ( link = 0, len = desktopLinks.length; link < len; link++ ) {
					desktopLinks[ link ].addEventListener( 'click', triggerToggleClose, true );
					desktopLinks[ link ].headerType = 'dropdown';
				}
			}

			for ( var item = 0; item < popupTriggerMobile.length; item++ ) {
				popupTriggerMobile[ item ].removeEventListener( 'click', popupTriggerClick, false );
				popupTriggerMobile[ item ].removeEventListener( 'click', astraNavMenuToggle );
				popupTriggerMobile[ item ].addEventListener( 'click', astraNavMenuToggle, false );
				popupTriggerMobile[ item ].trigger_type = 'mobile';
			}
			for ( var item = 0; item < popupTriggerDesktop.length; item++ ) {
				popupTriggerDesktop[ item ].removeEventListener( 'click', popupTriggerClick, false );
				popupTriggerDesktop[ item ].removeEventListener( 'click', astraNavMenuToggle );
				popupTriggerDesktop[ item ].addEventListener( 'click', astraNavMenuToggle, false );
				popupTriggerDesktop[ item ].trigger_type = 'desktop';
			}

			AstraToggleSetup();
		}

	}

	function triggerToggleClose( event ) {

		var headerType = event.currentTarget.headerType;

		switch( headerType ) {

			case 'dropdown':

				var popupTrigger = document.querySelectorAll( '.menu-toggle.toggled' );

				for ( var item = 0;  item < popupTrigger.length; item++ ) {

					popupTrigger[item].click();
				}
				break;
			case 'off-canvas':

				var popupClose = document.getElementById( 'menu-toggle-close' );

				popupClose.click();
				break;
			default:
				break;
		}
	}

	window.addEventListener( 'load', function() {
		init();
	} );
	document.addEventListener( 'astLayoutWidthChanged', function() {
		init();
	} );

	document.addEventListener( 'astPartialContentRendered', function() {

		menu_toggle_all = document.querySelectorAll( '.main-header-menu-toggle' );

		body.classList.remove("ast-main-header-nav-open");

		document.addEventListener( 'astMobileHeaderTypeChange', updateHeaderType, false );

		init();

	} );

	var mobile_width = ( null !== navigator.userAgent.match(/Android/i) && 'Android' === navigator.userAgent.match(/Android/i)[0] ) ? window.visualViewport.width : window.innerWidth;

	function AstraHandleResizeEvent() {

		var menu_offcanvas_close 	= document.getElementById('menu-toggle-close');
		var menu_dropdown_close 	= document.querySelector('.menu-toggle.toggled');
		var desktop_header_content	= document.querySelector('#masthead > #ast-desktop-header .ast-desktop-header-content');
		var elementor_editor 		= document.querySelector('.elementor-editor-active');

		if ( desktop_header_content ) {
			desktop_header_content.style.display = 'none';
		}
		var mobileResizeWidth = ( null !== navigator.userAgent.match(/Android/i) && 'Android' === navigator.userAgent.match(/Android/i)[0] ) ? window.visualViewport.width : window.innerWidth;

		if ( mobileResizeWidth !== mobile_width ) {
			if ( menu_dropdown_close && null === elementor_editor ) {
				menu_dropdown_close.click();
			}
			document.body.classList.remove( 'ast-main-header-nav-open', 'ast-popup-nav-open' );

			if( menu_offcanvas_close && null == elementor_editor ) {
				menu_offcanvas_close.click();
			}
		}

		updateHeaderBreakPoint();

		AstraToggleSetup();

	}

	window.addEventListener('resize', function(){
		// Skip resize event when keyboard display event triggers on devices.
		if( 'INPUT' !== document.activeElement.tagName ) {
			AstraHandleResizeEvent();
		}
	} );

	document.addEventListener('DOMContentLoaded', function () {
		AstraToggleSetup();
		/**
		 * Navigation Keyboard Navigation.
		 */
		var containerButton;
		if ( body.classList.contains('ast-header-break-point') ) {
			containerButton = document.getElementById( 'ast-mobile-header' );
		} else {
			containerButton = document.getElementById( 'ast-desktop-header' );
		}

		if( null !== containerButton ) {
			var containerMenu = containerButton.querySelector( '.navigation-accessibility' );
			navigation_accessibility( containerMenu, containerButton );
		}
	});

	var get_window_width = function () {

		return document.documentElement.clientWidth;
	}

	/* Add break point Class and related trigger */
	var updateHeaderBreakPoint = function () {

		// Content overrflowing out of screen can give incorrect window.innerWidth.
		// Adding overflow hidden and then calculating the window.innerWidth fixes the problem.
		var originalOverflow = body.style.overflow;
		body.style.overflow = 'hidden';
		var ww = get_window_width();
		body.style.overflow = originalOverflow;

		var break_point = astra.break_point;

		/**
		 * This case is when one hits a URL one after the other via `Open in New Tab` option
		 * Chrome returns the value of outer width as 0 in this case.
		 * This mis-calculates the width of the window and header seems invisible.
		 * This could be fixed by using `0 === ww` condition below.
		 */
		if (ww > break_point || 0 === ww) {
			//remove menu toggled class.
			if ( menu_toggle_all.length > 0 ) {

				for (var i = 0; i < menu_toggle_all.length; i++) {

					if( null !== menu_toggle_all[i] ) {
						menu_toggle_all[i].classList.remove('toggled');
					}
				}
			}
			body.classList.remove("ast-header-break-point");
			body.classList.add("ast-desktop");
			astraTriggerEvent(body, "astra-header-responsive-enabled");

		} else {

			body.classList.add("ast-header-break-point");
			body.classList.remove("ast-desktop");
			astraTriggerEvent(body, "astra-header-responsive-disabled")
		}
	}

	updateHeaderBreakPoint();

	AstraToggleSubMenu = function( event ) {

		event.preventDefault();


		if ('false' === event.target.getAttribute('aria-expanded') || ! event.target.getAttribute('aria-expanded')) {
			event.target.setAttribute('aria-expanded', 'true');
		} else {
			event.target.setAttribute('aria-expanded', 'false');
		}

		var parent_li = this.parentNode;

		if ( parent_li.classList.contains('ast-submenu-expanded') && document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link') ) {

			if (!this.classList.contains('ast-menu-toggle')) {

				var link = parent_li.querySelector('a').getAttribute('href');
				if ( '' !== link && '#' !== link) {
					window.location = link;
				}
			}
		}

		var parent_li_child = parent_li.querySelectorAll('.menu-item-has-children');
		for (var j = 0; j < parent_li_child.length; j++) {

			parent_li_child[j].classList.remove('ast-submenu-expanded');
			var parent_li_child_sub_menu = parent_li_child[j].querySelector('.sub-menu, .children');
			if( null !== parent_li_child_sub_menu ) {
				parent_li_child_sub_menu.style.display = 'none';
			}
		}

		var parent_li_sibling = parent_li.parentNode.querySelectorAll('.menu-item-has-children');
		for (var j = 0; j < parent_li_sibling.length; j++) {

			if (parent_li_sibling[j] != parent_li) {

				parent_li_sibling[j].classList.remove('ast-submenu-expanded');
				var all_sub_menu = parent_li_sibling[j].querySelectorAll('.sub-menu');
				for (var k = 0; k < all_sub_menu.length; k++) {
					all_sub_menu[k].style.display = 'none';
				}
			}
		}

		if (parent_li.classList.contains('menu-item-has-children') ) {
			astraToggleClass(parent_li, 'ast-submenu-expanded');
			if (parent_li.classList.contains('ast-submenu-expanded')) {
				parent_li.querySelector('.sub-menu').style.display = 'block';
			} else {
				parent_li.querySelector('.sub-menu').style.display = 'none';
			}
		}
	};

	AstraToggleSetup = function () {

		if( typeof astraAddon != 'undefined' && typeof astraToggleSetupPro === "function" ) {
			astraToggleSetupPro( mobileHeaderType, body, menu_click_listeners_nav );
		} else {
			var flag = false;
			var menuToggleAllLength;
			if ( 'off-canvas' === mobileHeaderType || 'full-width' === mobileHeaderType ) {
				// comma separated selector added, if menu is outside of Off-Canvas then submenu is not clickable, it work only for Off-Canvas area with dropdown style.
				var __main_header_all = document.querySelectorAll( '#ast-mobile-popup, #ast-mobile-header' );
				var menu_toggle_all = document.querySelectorAll('#ast-mobile-header .main-header-menu-toggle');

				menuToggleAllLength = menu_toggle_all.length;
			} else {
				var __main_header_all = document.querySelectorAll( '#ast-mobile-header' ),
					menu_toggle_all = document.querySelectorAll('#ast-mobile-header .main-header-menu-toggle');
					menuToggleAllLength = menu_toggle_all.length;
				flag = menuToggleAllLength > 0 ? false : true;

				menuToggleAllLength = flag ? 1 : menuToggleAllLength;

			}

			if ( menuToggleAllLength > 0 || flag ) {

				for (var i = 0; i < menuToggleAllLength; i++) {

					if ( ! flag ) {

						menu_toggle_all[i].setAttribute('data-index', i);

						if ( ! menu_click_listeners_nav[i] ) {
							menu_click_listeners_nav[i] = menu_toggle_all[i];
							menu_toggle_all[i].removeEventListener('click', astraNavMenuToggle);
							menu_toggle_all[i].addEventListener('click', astraNavMenuToggle, false);
						}
					}

					if ('undefined' !== typeof __main_header_all[i]) {

						// To handle the comma seprated selector added above we need this loop.
						for( var mainHeaderCount =0; mainHeaderCount  < __main_header_all.length; mainHeaderCount++ ){

							if (document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link')) {
								var astra_menu_toggle = __main_header_all[mainHeaderCount].querySelectorAll('ul.main-header-menu .menu-item-has-children > .menu-link, ul.main-header-menu .ast-menu-toggle');
							} else {
								var astra_menu_toggle = __main_header_all[mainHeaderCount].querySelectorAll('ul.main-header-menu .ast-menu-toggle');
							}
							// Add Eventlisteners for Submenu.
							if (astra_menu_toggle.length > 0) {

								for (var j = 0; j < astra_menu_toggle.length; j++) {
									astra_menu_toggle[j].removeEventListener('click', AstraToggleSubMenu);
									astra_menu_toggle[j].addEventListener('click', AstraToggleSubMenu, false);
								}
							}
						}
					}
				}
			}
		}
	};

	astraNavMenuToggle = function ( event ) {

		if( typeof astraAddon != 'undefined' ) {
			astraNavMenuTogglePro( event, body, mobileHeaderType, this );
		} else {

			event.preventDefault();
			var __main_header_all = document.querySelectorAll('#masthead > #ast-mobile-header .main-header-bar-navigation');
			menu_toggle_all 	 = document.querySelectorAll( '#masthead > #ast-mobile-header .main-header-menu-toggle' )
			var event_index = '0';

			if ( null !== this.closest( '#ast-fixed-header' ) ) {

				__main_header_all = document.querySelectorAll('#ast-fixed-header > #ast-mobile-header .main-header-bar-navigation');
				menu_toggle_all 	 = document.querySelectorAll( '#ast-fixed-header .main-header-menu-toggle' )

				event_index = '0';
			}

			if ('undefined' === typeof __main_header_all[event_index]) {
				return false;
			}
			var menuHasChildren = __main_header_all[event_index].querySelectorAll('.menu-item-has-children');
			for (var i = 0; i < menuHasChildren.length; i++) {
				menuHasChildren[i].classList.remove('ast-submenu-expanded');
				var menuHasChildrenSubMenu = menuHasChildren[i].querySelectorAll('.sub-menu');
				for (var j = 0; j < menuHasChildrenSubMenu.length; j++) {
					menuHasChildrenSubMenu[j].style.display = 'none';
				}
			}

			var menu_class = this.getAttribute('class') || '';

			if ( menu_class.indexOf('main-header-menu-toggle') !== -1 ) {
				astraToggleClass(__main_header_all[event_index], 'toggle-on');
				astraToggleClass(menu_toggle_all[event_index], 'toggled');
				if (__main_header_all[event_index].classList.contains('toggle-on')) {
					__main_header_all[event_index].style.display = 'block';
					body.classList.add("ast-main-header-nav-open");
				} else {
					__main_header_all[event_index].style.display = '';
					body.classList.remove("ast-main-header-nav-open");
				}
			}
		}
	};

	body.addEventListener("astra-header-responsive-enabled", function () {

		var __main_header_all = document.querySelectorAll('.main-header-bar-navigation');

		if (__main_header_all.length > 0) {

			for (var i = 0; i < __main_header_all.length; i++) {
				if (null != __main_header_all[i]) {
					__main_header_all[i].classList.remove('toggle-on');
					__main_header_all[i].style.display = '';
				}

				var sub_menu = __main_header_all[i].getElementsByClassName('sub-menu');
				for (var j = 0; j < sub_menu.length; j++) {
					sub_menu[j].style.display = '';
				}
				var child_menu = __main_header_all[i].getElementsByClassName('children');
				for (var k = 0; k < child_menu.length; k++) {
					child_menu[k].style.display = '';
				}

				var searchIcons = __main_header_all[i].getElementsByClassName('ast-search-menu-icon');
				for (var l = 0; l < searchIcons.length; l++) {
					searchIcons[l].classList.remove('ast-dropdown-active');
					searchIcons[l].style.display = '';
				}
			}
		}
	}, false);

	var get_browser = function () {
	    var ua = navigator.userAgent,tem,M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])) {
	        tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return;
	    }
	    if( 'Chrome'  === M[1] ) {
	        tem = ua.match(/\bOPR|Edge\/(\d+)/)
	        if(tem != null)   {
	        	return;
	        	}
	        }
	    M = M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem = ua.match(/version\/(\d+)/i)) != null) {
	    	M.splice(1,1,tem[1]);
	    }

	    if( 'Safari' === M[0] && M[1] < 11 ) {
			document.body.classList.add( "ast-safari-browser-less-than-11" );
	    }
	}

	get_browser();

	/* Search Script */
	var SearchIcons = document.getElementsByClassName( 'astra-search-icon' );
	for (var i = 0; i < SearchIcons.length; i++) {

		SearchIcons[i].onclick = function(event) {
            if ( this.classList.contains( 'slide-search' ) ) {
                event.preventDefault();
                var sibling = this.parentNode.parentNode.parentNode.querySelector( '.ast-search-menu-icon' );
                if ( ! sibling.classList.contains( 'ast-dropdown-active' ) ) {
                    sibling.classList.add( 'ast-dropdown-active' );
                    sibling.querySelector( '.search-field' ).setAttribute('autocomplete','off');
                    setTimeout(function() {
                     sibling.querySelector( '.search-field' ).focus();
                    },200);
                } else {
                	var searchTerm = sibling.querySelector( '.search-field' ).value || '';
	                if( '' !== searchTerm ) {
    		            sibling.querySelector( '.search-form' ).submit();
                    }
                    sibling.classList.remove( 'ast-dropdown-active' );
                }
            }
        }
	}
	var SearchInputs = document.querySelectorAll( '.search-field' );
	SearchInputs.forEach(input => {
		input.addEventListener('focus', function (e) {
			var sibling = this.parentNode.parentNode.parentNode.querySelector( '.ast-search-menu-icon' );
			if ( sibling ) {
				astraToggleClass( sibling, 'ast-dropdown-active' );
			}
		});
		input.addEventListener('blur', function (e) {
			var sibling = this.parentNode.parentNode.parentNode.querySelector( '.ast-search-menu-icon' );
			if ( sibling ) {
				sibling.classList.remove( 'ast-dropdown-active' );
				astraToggleClass( sibling, 'ast-dropdown-active' );
			}
		});
	});

	/* Hide Dropdown on body click*/
	body.onclick = function( event ) {
		if ( typeof event.target.classList !==  'undefined' ) {
			if ( ! event.target.classList.contains( 'ast-search-menu-icon' ) && astraGetParents( event.target, '.ast-search-menu-icon' ).length === 0 && astraGetParents( event.target, '.ast-search-icon' ).length === 0  ) {
				var dropdownSearchWrap = document.getElementsByClassName( 'ast-search-menu-icon' );
				for (var i = 0; i < dropdownSearchWrap.length; i++) {
					dropdownSearchWrap[i].classList.remove( 'ast-dropdown-active' );
				}
			}
		}
	}

	/**
	 * Navigation Keyboard Navigation.
	 */
	function navigation_accessibility(containerMenu, containerButton) {
		if (!containerMenu || !containerButton) {
			return;
		}
		var button = containerButton.getElementsByTagName('button')[0] || containerButton.getElementsByTagName('a')[0];
		if (!button || (button.classList.contains('astra-search-icon'))) {
			return;
		}
		var menu = containerMenu.getElementsByTagName('ul')[0];

		// Hide menu toggle button if menu is empty and return early.
		if (!menu) {
			button.style.display = 'none';
			return;
		}

		if (!menu.className.includes('nav-menu')) {
			menu.className += ' nav-menu';
		}

		document.addEventListener('DOMContentLoaded', function () {
			if ('off-canvas' === mobileHeaderType) {
				var popupClose = document.getElementById('menu-toggle-close');
				if (popupClose) {
					popupClose.onclick = function () {
						var toggled = containerMenu.className.includes('toggled');
						containerMenu.className = toggled ? containerMenu.className.replace(' toggled', '') : containerMenu.className + ' toggled';
						button.setAttribute('aria-expanded', toggled ? 'false' : 'true');
						menu.setAttribute('aria-expanded', toggled ? 'false' : 'true');
					};
				}
			}
		});

		button.onclick = function () {
			var toggled = containerMenu.className.includes('toggled');
			containerMenu.className = toggled ? containerMenu.className.replace(' toggled', '') : containerMenu.className + ' toggled';
			button.setAttribute('aria-expanded', toggled ? 'false' : 'true');
			menu.setAttribute('aria-expanded', toggled ? 'false' : 'true');
		};

		if (!astra.is_header_footer_builder_active) {

			// Get all the link elements within the menu.
			var links = menu.getElementsByTagName('a');
			var subMenus = menu.getElementsByTagName('ul');

			// Set menu items with submenus to aria-haspopup="true".
			for (var i = 0, len = subMenus.length; i < len; i++) {
				subMenus[i].parentNode.setAttribute('aria-haspopup', 'true');
			}

			// Each time a menu link is focused or blurred, toggle focus.
			for (var i = 0, len = links.length; i < len; i++) {
				links[i].addEventListener('focus', toggleFocus, true);
				links[i].addEventListener('blur', toggleFocus, true);
				links[i].addEventListener('click', toggleClose, true);
			}
		}

		if (astra.is_header_footer_builder_active) {
			tabNavigation();
		}
	}

	// Tab navigation for accessibility.
	function tabNavigation() {
		const dropdownToggleLinks = document.querySelectorAll('nav.site-navigation .menu-item-has-children > a .ast-header-navigation-arrow');
		const siteNavigationSubMenu = document.querySelectorAll('nav.site-navigation .sub-menu');
		const menuLi = document.querySelectorAll('nav.site-navigation .menu-item-has-children');
		const megaMenuFullWidth = document.querySelectorAll('.astra-full-megamenu-wrapper');

		if (dropdownToggleLinks) {
			dropdownToggleLinks.forEach(element => {
				element.addEventListener('keydown', function (e) {
					if ('Enter' === e.key) {
						// Check if the user is on a mobile device and prevent default and stop propagation if true.
						if ( /Mobi|Android|iPad|iPhone/i.test( navigator.userAgent ) ) {
							e.preventDefault();
							e.stopPropagation();
						}

						const closestLi = e.target.closest('li');
						const subMenu = closestLi.querySelector('.sub-menu');
						const isMegaMenu = subMenu && subMenu.classList.contains('astra-megamenu');

						setTimeout(() => {
							if (!isMegaMenu) {
								subMenu.classList.toggle('toggled-on');
								closestLi.classList.toggle('ast-menu-hover');
							} else {
								const fullMegaMenuWrapper = closestLi.querySelector('.astra-full-megamenu-wrapper');
								if (subMenu) subMenu.classList.toggle('astra-megamenu-focus');
								if (fullMegaMenuWrapper) fullMegaMenuWrapper.classList.toggle('astra-megamenu-wrapper-focus');
								closestLi.classList.toggle('ast-menu-hover');
							}

							const ariaExpanded = e.target.getAttribute('aria-expanded');
							e.target.setAttribute('aria-expanded', ariaExpanded === 'false' || !ariaExpanded ? 'true' : 'false');
						}, 10);
					}
				});
			});

			if (siteNavigationSubMenu || menuLi) {
				document.addEventListener('click', function (e) {
					closeNavigationMenu(siteNavigationSubMenu, dropdownToggleLinks, menuLi, megaMenuFullWidth);
				}, false);

				document.addEventListener('keydown', function (e) {
					if ('Escape' === e.key) {
						closeNavigationMenu(siteNavigationSubMenu, dropdownToggleLinks, menuLi, megaMenuFullWidth);
					}
				}, false);
			}
		}

		const allParentMenu = document.querySelectorAll('nav.site-navigation .ast-nav-menu > .menu-item-has-children > a .ast-header-navigation-arrow');

		if (allParentMenu) {
			allParentMenu.forEach(element => {
				element.addEventListener('keydown', function (e) {
					if (!e.target.closest('li').classList.contains('ast-menu-hover') && 'Enter' === e.key) {
						closeNavigationMenu(siteNavigationSubMenu, dropdownToggleLinks, menuLi, megaMenuFullWidth);
					}
				}, false);
			});
		}
	}

	function closeNavigationMenu(siteNavigationSubMenu, dropdownToggleLinks, menuLi, megaMenuFullWidth) {
		if (siteNavigationSubMenu) {
			siteNavigationSubMenu.forEach(element => {
				element.classList.remove('astra-megamenu-focus');
				element.classList.remove('toggled-on');
			});
		}

		if (menuLi) {
			menuLi.forEach(element => {
				element.classList.remove('ast-menu-hover');
			});
		}

		if (megaMenuFullWidth) {
			megaMenuFullWidth.forEach(element => {
				element.classList.remove('astra-megamenu-wrapper-focus')
			});
		}


		if (dropdownToggleLinks) {
			dropdownToggleLinks.forEach(element => {
				element.setAttribute('aria-expanded', 'false');
			});
		}
	}

	/**
     * Close the Toggle Menu on Click on hash (#) link.
     *
     * @since 1.3.2
     * @return void
     */
	function toggleClose( )
	{
		var self = this || '',
			hash = '#';

		if( self && ! self.classList.contains('astra-search-icon') && null === self.closest('.ast-builder-menu') ) {
			var link = String( self );
			if( link.indexOf( hash ) !== -1 ) {
				var link_parent = self.parentNode;
				if ( body.classList.contains('ast-header-break-point') ) {
					if( ! ( document.querySelector('header.site-header').classList.contains('ast-builder-menu-toggle-link') && link_parent.classList.contains('menu-item-has-children') ) ) {
						/* Close Builder Header Menu */
						var builder_header_menu_toggle = document.querySelector( '.main-header-menu-toggle' );
						builder_header_menu_toggle.classList.remove( 'toggled' );

						var main_header_bar_navigation = document.querySelector( '.main-header-bar-navigation' );
						main_header_bar_navigation.classList.remove( 'toggle-on' );

						main_header_bar_navigation.style.display = 'none';

						astraTriggerEvent( document.querySelector('body'), 'astraMenuHashLinkClicked' );
					}

				} else {
					while ( -1 === self.className.indexOf( 'nav-menu' ) ) {
						// On li elements toggle the class .focus.
						if ( 'li' === self.tagName.toLowerCase() ) {
							if ( -1 !== self.className.indexOf( 'focus' ) ) {
								self.className = self.className.replace( ' focus', '' );
							}
						}
						self = self.parentElement;
					}
				}
			}
		}
	}

	/**
	 * Sets or removes .focus class on an element on focus.
	 */
	function toggleFocus() {
		var self = this;
		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'navigation-accessibility' ) ) {
			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				self.classList.toggle('focus');
			}
			self = self.parentElement;
		}
	}

	if( ! astra.is_header_footer_builder_active ) {

		/* Add class if mouse clicked and remove if tab pressed */
		if ( 'querySelector' in document && 'addEventListener' in window ) {
			body.addEventListener( 'mousedown', function() {
				body.classList.add( 'ast-mouse-clicked' );
			} );

			body.addEventListener( 'keydown', function() {
				body.classList.remove( 'ast-mouse-clicked' );
			} );
		}
	}

	/**
	 * Scroll to specific hash link.
	 *
	 * @since x.x.x
	 */
	if ( astra.is_scroll_to_id ) {
		// Calculate the offset top of an element, accounting for nested elements.
		const getOffsetTop = (element) => {
			let offsetTop = 0;
			while (element) {
				offsetTop += element.offsetTop;
				element = element.offsetParent;
			}
			return offsetTop;
		}

		const scrollToIDHandler = ( e, hash = null ) => {

			let offset = 0;
			const siteHeader = document.querySelector('.site-header');

			if (siteHeader) {

				// Check and add offset to scroll top if header is sticky.
				const stickyHeaders = siteHeader.querySelectorAll(
					'div[data-stick-support]'
				);

				if ( stickyHeaders.length > 0 ) {
					stickyHeaders.forEach( ( header ) => ( offset += header.clientHeight ) );
				} else if ( typeof astraAddon !== 'undefined' && ! ( Number( astraAddon.sticky_hide_on_scroll ) && ! document?.querySelector( '.ast-header-sticked' ) ) ) {
					const fixedHeader = document.querySelector( '#ast-fixed-header' );
					if ( fixedHeader ) {
						offset = fixedHeader?.clientHeight;
						if ( Number( astraAddon?.header_main_shrink ) ) {
							const headers = fixedHeader?.querySelectorAll(
								'.ast-above-header-wrap, .ast-below-header-wrap'
							);
							headers?.forEach( () => ( offset -= 10 ) );
						}
					}
				}

				const href = hash ? hash : e.target?.closest( 'a' ).hash;
				if (href) {
					const scrollId = document.querySelector(href);
					if (scrollId) {
						const elementOffsetTop = getOffsetTop( scrollId );
						if ( typeof astraAddon !== 'undefined' && Number( astraAddon.sticky_hide_on_scroll ) && window?.scrollY  < elementOffsetTop ) {
							offset = 0;
						}

						const scrollOffsetTop = elementOffsetTop - offset;
						if( scrollOffsetTop ) {
							astraSmoothScroll( e, scrollOffsetTop );
						}
					}
				}
			}
		}

		let hashLinks = [];
		const links = document.querySelectorAll(
			'a[href*="#"]:not([href="#"]):not([href="#0"]):not([href*="uagb-tab"]):not(.uagb-toc-link__trigger):not(.skip-link):not(.nav-links a):not([href*="tab-"])'
		);
		if (links) {
			for (const link of links) {
				if (link.href.split("#")[0] !== location.href.split("#")[0]) {
					// Store the hash
					hashLinks.push({
						hash: link.hash,
						url: link.href.split("#")[0],
					});
				} else if (link.hash !== "") {
					link.addEventListener("click", scrollToIDHandler);
				}
			}
		}

		window.addEventListener('DOMContentLoaded', (event) => {
			for (let link of hashLinks) {
				if (window.location.href.split('#')[0] === link.url) {
					const siteHeader = document.querySelector('.site-header');
					let offset = 0;
	
					// Check and add offset to scroll top if header is sticky.
					const headerHeight = siteHeader.querySelectorAll('div[data-stick-support]');
					if (headerHeight) {
						headerHeight.forEach(single => {
							offset += single.clientHeight;
						});
					}

					const scrollId = document.querySelector(link.hash);
					if (scrollId) {
						const scrollOffsetTop = getOffsetTop(scrollId) - offset;
						if (scrollOffsetTop) {
							astraSmoothScroll(event, scrollOffsetTop);
						}
					}
				}
			}

			// If there is a hash in the URL when the page loads, scroll to that element after a short delay.
			if ( location.hash ) {
				setTimeout( () => scrollToIDHandler( new Event( 'click' ), location.hash ), 750 );
			}
		});
	}

	/**
	 * Scroll to top.
	 *
	 * @since x.x.x
	 */
	if ( astra.is_scroll_to_top ) {
		var masthead     = document.querySelector( '#page header' );
		var astScrollTop = document.getElementById( 'ast-scroll-top' );

		astScrollToTopHandler(masthead, astScrollTop);

		window.addEventListener('scroll', function () {
			astScrollToTopHandler(masthead, astScrollTop);
		});

		astScrollTop.onclick = function(e){
			astraSmoothScroll( e, 0 );
		};

		astScrollTop.addEventListener( 'keydown' , function(e) {
			if ( e.key === 'Enter') {
				astraSmoothScroll( e, 0 );
			}
		});
	}

	if ( astra?.is_dark_palette ) {
		document.documentElement.classList.add("astra-dark-mode-enable");
	} else {
		document.documentElement.classList.remove("astra-dark-mode-enable");
	}

	/**
	 * To remove the blank space when the store notice gets dismissed.
	 * To adjust the height of the store notice when hanged over top.
	 *
	 * @since x.x.x
	 */
	window.addEventListener('DOMContentLoaded', (event) => {
		const isHangOverTopNotice = document.querySelector('.ast-woocommerce-store-notice-hanged');
		const adjustBodyHeight = () => {
			const storeNotice = document.querySelector('.woocommerce-store-notice[data-position="hang-over-top"]');
			document.body.style.paddingTop = `${storeNotice?.clientHeight || 0}px`;
		}

		if (isHangOverTopNotice) {	
			window.addEventListener('resize', adjustBodyHeight);
			setTimeout(() => adjustBodyHeight(), 0);
		}

		document
			.querySelector('.woocommerce-store-notice__dismiss-link')
			?.addEventListener('click', () => {
				if ( typeof wp === 'undefined' || ! wp?.customize ) {
					document.body.classList.remove('ast-woocommerce-store-notice-hanged');
					window.removeEventListener('resize', adjustBodyHeight);
					document.body.style.paddingTop = 0;
				}
			});
	});

})();

// Accessibility improvement for menu items.
document.addEventListener('DOMContentLoaded', function () {
    const submenuToggles = document.querySelectorAll('.menu-link .dropdown-menu-toggle');

	const menuItemsWithSubmenu = document.querySelectorAll('.menu-item-has-children > a');

	// Ensuring the submenu toggle action is handled with appropriate CSS, with cross browser compatibility for Enter key press.
    menuItemsWithSubmenu.forEach(item => {
        item.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                const submenu = item.nextElementSibling;
                if (submenu && submenu.classList.contains('sub-menu')) {
                    submenu.classList.toggle('ast-visible');
                    const ariaExpanded = item.getAttribute('aria-expanded') === 'false' ? 'true' : 'false';
                    item.setAttribute('aria-expanded', ariaExpanded);
                }
            }
        });
    });

    // Add event listeners for focus, blur, and keydown events.
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('focus', () => updateAriaExpanded(toggle));
        toggle.addEventListener('blur', () => updateAriaExpanded(toggle));
        toggle.addEventListener('keydown', event => {
            if (event.key === 'Enter') {
                event.preventDefault();
                toggleAriaExpanded(toggle);
            }
        });
    });

    // Add event listener for Escape key press to close all submenus.
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeAllSubmenus();
        }
    });

    /**
     * Updates the `aria-expanded` attribute based on submenu visibility.
     * @param {HTMLElement} toggle - The toggle element.
     */
    function updateAriaExpanded(toggle) {
        const menuItemLink = toggle.closest('.menu-link');
        const submenu = menuItemLink.nextElementSibling;
        const isSubmenuVisible = submenu.classList.contains('toggled-on');
        menuItemLink.setAttribute('aria-expanded', isSubmenuVisible ? 'true' : 'false');
    }

    /**
     * Toggles the `aria-expanded` attribute for a given toggle element.
     * @param {HTMLElement} toggle - The toggle element.
     */
    function toggleAriaExpanded(toggle) {
        const menuItemLink = toggle.closest('.menu-link');
        const currentState = menuItemLink.getAttribute('aria-expanded');
        menuItemLink.setAttribute('aria-expanded', currentState === 'true' ? 'false' : 'true');
    }

    /**
     * Closes all submenus by updating the `aria-expanded` attribute.
     */
    function closeAllSubmenus() {
        submenuToggles.forEach(toggle => updateAriaExpanded(toggle));
    }

	// This event listener is triggered when the device orientation changes, and it dispatches a 'resize' event to ensure layout adjustments are made.
	window.addEventListener( 'orientationchange', () => {
		setTimeout( () => window.dispatchEvent( new Event( 'resize' ) ), 50 );
	} );
});

// Accessibility improvement for product card quick view and add to cart buttons.
document.addEventListener('DOMContentLoaded', () => {
    const thumbnailWraps = document.querySelectorAll('.astra-shop-thumbnail-wrap');

    thumbnailWraps.forEach(wrap => {
        const focusableElements = wrap.querySelectorAll('a, span');

        focusableElements.forEach(el => {
            el.addEventListener('focus', () => {
                wrap.querySelectorAll('.ast-on-card-button, .ast-quick-view-trigger').forEach(btn => {
                    btn.style.opacity = '1';
                    btn.style.visibility = 'visible';
                    btn.style.borderStyle = 'none';
                });
            });

            el.addEventListener('blur', () => {
                // Added Check to check if child elements are still focused.
                const isAnyFocused = Array.from(focusableElements).some(child => child === document.activeElement);
                if (!isAnyFocused) {
                    wrap.querySelectorAll('.ast-on-card-button, .ast-quick-view-trigger').forEach(btn => {
                        btn.style.opacity = '';
                        btn.style.visibility = '';
                    });
                }
            });
        });
    });
});
