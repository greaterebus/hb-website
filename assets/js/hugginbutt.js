/**
 * HugginButt front-end interactions: mobile nav toggle + submenu accordion,
 * testimonial dot carousel. Vanilla JS, no dependencies.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initMobileNav();
		initCategoryCarousels();
		initTestimonialCarousel();
		initShopFilterHighlight();
		initShopAjaxFilters();
		initAddToCartToast();
	} );

	/**
	 * Parchment-styled toast confirming an item was added to the cart.
	 * WooCommerce's AJAX add-to-cart (wc-add-to-cart.js) triggers
	 * "added_to_cart" on document.body via jQuery, not as a native DOM
	 * event, so this has to listen through jQuery rather than
	 * addEventListener - jQuery is always present wherever WooCommerce's
	 * own add-to-cart script is (both are enqueued together).
	 */
	function initAddToCartToast() {
		if ( ! window.jQuery ) { return; }

		var region = null;
		var hideTimer = null;
		var lastClick = null;

		// Capture phase, so this still records the click position even if
		// something else stops the event from bubbling further.
		document.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.add_to_cart_button' ) ) {
				lastClick = { x: event.clientX, y: event.clientY };
			}
		}, true );

		window.jQuery( document.body ).on( 'added_to_cart', function ( event, fragments, cartHash, $button ) {
			var message = $button && $button.attr ? $button.attr( 'data-success_message' ) : '';
			showToast( message || 'Added to your loot.', lastClick );
		} );

		function getRegion() {
			if ( region ) { return region; }

			region = document.createElement( 'div' );
			region.className = 'hb-toast-region';
			region.setAttribute( 'role', 'status' );
			region.setAttribute( 'aria-live', 'polite' );
			document.body.appendChild( region );
			return region;
		}

		function showToast( message, atPoint ) {
			var toastRegion = getRegion();
			var existing = toastRegion.querySelector( '.hb-toast' );
			if ( existing ) { existing.remove(); }

			var toast = document.createElement( 'div' );
			toast.className = 'hb-toast';
			toast.innerHTML =
				'<span class="hb-toast__icon" aria-hidden="true"></span>' +
				'<span class="hb-toast__message"></span>';
			toast.querySelector( '.hb-toast__message' ).textContent = message;
			toastRegion.appendChild( toast );

			positionNearPoint( toast, atPoint );

			// Force layout so the "is-visible" transition actually runs
			// instead of jumping straight to its end state.
			toast.getBoundingClientRect();
			toast.classList.add( 'is-visible' );

			clearTimeout( hideTimer );
			hideTimer = setTimeout( function () {
				toast.classList.remove( 'is-visible' );
				toast.addEventListener( 'transitionend', function handler() {
					toast.remove();
					toast.removeEventListener( 'transitionend', handler );
				} );
			}, 3200 );
		}

		/**
		 * Places the toast just above and to the right of where the Add to
		 * Cart button was clicked, clamped so it never runs off the edge of
		 * the viewport. Falls back to the bottom-right corner (the region's
		 * own default CSS position) if we somehow have no click point, e.g.
		 * "added_to_cart" fired from something other than a real click.
		 */
		function positionNearPoint( toast, point ) {
			if ( ! point ) { return; }

			var margin = 16;
			var offsetX = 20;
			var offsetY = 20;
			var rect = toast.getBoundingClientRect();
			var width = rect.width || 320;
			var height = rect.height || 60;

			var left = point.x + offsetX;
			var top = point.y - height - offsetY;

			left = Math.min( left, window.innerWidth - width - margin );
			left = Math.max( left, margin );
			top = Math.max( top, margin );
			top = Math.min( top, window.innerHeight - height - margin );

			toast.style.position = 'fixed';
			toast.style.left = left + 'px';
			toast.style.top = top + 'px';
			toast.style.right = 'auto';
			toast.style.bottom = 'auto';
		}
	}

	/**
	 * Marks the active category link in the Shop Filters sidebar. The
	 * category widget has no way to know product_cat is a query param on
	 * the Shop page rather than a separate archive, so it never applies
	 * its own "current-cat" class here - this fills that in from the URL.
	 * Re-run after each AJAX filter swap, so stale highlighting is cleared
	 * first.
	 */
	function initShopFilterHighlight() {
		var links = document.querySelectorAll( '.primary-sidebar .product-categories a' );
		if ( ! links.length ) { return; }

		links.forEach( function ( link ) {
			link.classList.remove( 'hb-cat-active' );
			if ( link.parentElement ) {
				link.parentElement.classList.remove( 'current-cat' );
			}
		} );

		var activeSlug = new URLSearchParams( window.location.search ).get( 'product_cat' );

		links.forEach( function ( link ) {
			var linkParams = new URL( link.href, window.location.origin ).searchParams;
			var isAll = link.closest( '.cat-item-all' );
			var matches = activeSlug ? linkParams.get( 'product_cat' ) === activeSlug : !! isAll;

			if ( matches ) {
				link.classList.add( 'hb-cat-active' );
				if ( link.parentElement ) {
					link.parentElement.classList.add( 'current-cat' );
				}
			}
		} );
	}

	/**
	 * Lets shoppers filter the Shop page (by category or price) without a
	 * full page reload: intercepts the sidebar's category links and the
	 * price filter form, fetches the same filtered Shop URL in the
	 * background, and swaps in just the new .site-main content. Falls back
	 * to a normal navigation if anything about the fetch goes wrong.
	 */
	function initShopAjaxFilters() {
		var main = document.querySelector( 'body.woocommerce-shop .site-main' );
		if ( ! main || ! window.fetch || ! window.DOMParser ) { return; }

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest( '.primary-sidebar .product-categories a' );
			if ( ! link ) { return; }

			event.preventDefault();
			loadShopResults( link.href, true );
		} );

		document.addEventListener( 'submit', function ( event ) {
			var form = event.target;
			if ( ! form.matches( '.primary-sidebar .price_slider_wrapper form' ) ) { return; }

			event.preventDefault();
			var params = new URLSearchParams( new FormData( form ) ).toString();
			var action = form.getAttribute( 'action' ) || window.location.href;
			var url    = action + ( action.indexOf( '?' ) > -1 ? '&' : '?' ) + params;
			loadShopResults( url, true );
		} );

		window.addEventListener( 'popstate', function () {
			loadShopResults( window.location.href, false );
		} );
	}

	function loadShopResults( url, pushState ) {
		var main = document.querySelector( 'body.woocommerce-shop .site-main' );
		if ( ! main ) { window.location.href = url; return; }

		main.setAttribute( 'aria-busy', 'true' );

		var shuffleOut = playCardShuffleOut( main );
		var request = window.fetch( url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } } )
			.then( function ( response ) {
				if ( ! response.ok ) { throw new Error( 'Shop filter request failed' ); }
				return response.text();
			} );

		Promise.all( [ request, shuffleOut ] )
			.then( function ( results ) {
				var html = results[0];
				var newMain = new window.DOMParser().parseFromString( html, 'text/html' )
					.querySelector( 'body.woocommerce-shop .site-main' );

				if ( ! newMain ) { throw new Error( 'No .site-main found in response' ); }

				main.innerHTML = newMain.innerHTML;
				main.removeAttribute( 'aria-busy' );

				if ( pushState ) {
					window.history.pushState( {}, '', url );
				}

				initShopFilterHighlight();
				playCardShuffleIn( main );
			} )
			.catch( function () {
				window.location.href = url;
			} );
	}

	/**
	 * Flips the current product cards away like a dealer sweeping a hand off
	 * the table, staggered slightly per card. Resolves once the longest
	 * stagger + animation has finished, so the swap always waits for at
	 * least this flourish even when the response comes back instantly.
	 * Skips straight to resolved for prefers-reduced-motion.
	 */
	function playCardShuffleOut( main ) {
		var cards = main.querySelectorAll( '.hb-product-card' );
		var prefersReduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		if ( ! cards.length || prefersReduced ) {
			return Promise.resolve();
		}

		var stagger = 25;
		var duration = 280;

		cards.forEach( function ( card, index ) {
			card.style.animationDelay = Math.min( index * stagger, 200 ) + 'ms';
			card.classList.add( 'hb-card-out' );
		} );

		var totalTime = Math.min( ( cards.length - 1 ) * stagger, 200 ) + duration;
		return new Promise( function ( resolve ) { setTimeout( resolve, totalTime ); } );
	}

	/**
	 * Deals the new cards back in, staggered like they're being dealt from
	 * a deck.
	 */
	function playCardShuffleIn( main ) {
		var cards = main.querySelectorAll( '.hb-product-card' );
		var prefersReduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		if ( ! cards.length || prefersReduced ) { return; }

		var stagger = 35;

		cards.forEach( function ( card, index ) {
			card.style.animationDelay = Math.min( index * stagger, 260 ) + 'ms';
			card.classList.add( 'hb-card-in' );
			card.addEventListener( 'animationend', function handler() {
				card.classList.remove( 'hb-card-in' );
				card.style.animationDelay = '';
				card.removeEventListener( 'animationend', handler );
			} );
		} );
	}

	function initCategoryCarousels() {
		document.querySelectorAll( '[data-hb-category-carousel]' ).forEach( function ( carousel ) {
			var viewport = carousel.querySelector( '[data-hb-category-viewport]' );
			var previous = carousel.querySelector( '[data-hb-category-prev]' );
			var next = carousel.querySelector( '[data-hb-category-next]' );
			if ( ! viewport || ! previous || ! next ) { return; }

			function scrollAmount() {
				var card = viewport.querySelector( '.hb-category' );
				var grid = viewport.querySelector( '.hb-categories__grid' );
				var gap = grid ? parseFloat( window.getComputedStyle( grid ).columnGap ) || 0 : 0;
				return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth * 0.75;
			}
			function updateButtons() {
				var maxScroll = Math.max( 0, viewport.scrollWidth - viewport.clientWidth );
				previous.disabled = viewport.scrollLeft <= 2;
				next.disabled = viewport.scrollLeft >= maxScroll - 2;
				carousel.classList.toggle( 'has-overflow', maxScroll > 2 );
			}

			function fitWholeCards() {
				var card = viewport.querySelector( '.hb-category' );
				var grid = viewport.querySelector( '.hb-categories__grid' );
				if ( ! card || ! grid ) { return; }

				viewport.style.width = '100%';
				var available = viewport.clientWidth;
				var cardWidth = card.getBoundingClientRect().width;
				var gap = parseFloat( window.getComputedStyle( grid ).columnGap ) || 0;
				var visibleCards = Math.max( 1, Math.floor( ( available + gap ) / ( cardWidth + gap ) ) );
				var fittedWidth = visibleCards * cardWidth + ( visibleCards - 1 ) * gap;
				var currentIndex = Math.round( viewport.scrollLeft / ( cardWidth + gap ) );

				viewport.style.width = Math.min( available, fittedWidth ) + 'px';
				viewport.scrollLeft = currentIndex * ( cardWidth + gap );
				updateButtons();
			}

			previous.addEventListener( 'click', function () { viewport.scrollBy( { left: -scrollAmount(), behavior: 'smooth' } ); } );
			next.addEventListener( 'click', function () { viewport.scrollBy( { left: scrollAmount(), behavior: 'smooth' } ); } );
			viewport.addEventListener( 'scroll', updateButtons, { passive: true } );
			window.addEventListener( 'resize', fitWholeCards );
			fitWholeCards();
		} );
	}
	function initMobileNav() {
		var toggle = document.querySelector( '.hb-header__menu-toggle' );
		var nav = document.getElementById( 'hb-primary-nav' );

		if ( ! toggle || ! nav ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
			nav.classList.toggle( 'is-open', ! isOpen );
		} );

		var parentLinks = nav.querySelectorAll( '.menu-item-has-children > a' );
		parentLinks.forEach( function ( link ) {
			link.addEventListener( 'click', function ( event ) {
				if ( window.innerWidth > 782 ) {
					return;
				}
				var parentItem = link.parentElement;
				if ( ! parentItem.classList.contains( 'is-open' ) ) {
					event.preventDefault();
					parentItem.classList.add( 'is-open' );
				}
			} );
		} );
	}

	function initTestimonialCarousel() {
		var carousels = document.querySelectorAll( '[data-hb-carousel]' );

		carousels.forEach( function ( carousel ) {
			var slides = carousel.querySelectorAll( '.hb-testimonial-slide' );
			var dots = carousel.querySelectorAll( '[data-hb-slide]' );

			if ( slides.length < 2 ) {
				return;
			}

			dots.forEach( function ( dot ) {
				dot.addEventListener( 'click', function () {
					var index = parseInt( dot.getAttribute( 'data-hb-slide' ), 10 );

					slides.forEach( function ( slide, slideIndex ) {
						slide.classList.toggle( 'is-active', slideIndex === index );
					} );
					dots.forEach( function ( otherDot ) {
						otherDot.classList.toggle( 'is-active', otherDot === dot );
					} );
				} );
			} );
		} );
	}
} )();
