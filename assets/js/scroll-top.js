( function() {
	'use strict';

	var scrollBtn = document.querySelector( '.oly-scroll-up' );

	var trackScroll = function() {
		var scrolled = window.pageYOffset,
			coords = '100';

		if ( scrolled > coords ) {
			scrollBtn.style.opacity = '1';
			scrollBtn.style.visibility = 'visible';
		}

		if (scrolled < coords) {
			scrollBtn.style.opacity = '0';
			scrollBtn.style.visibility = 'hidden';
		}
	};

	// Function to animate the scroll
	var smoothScroll = function (anchor, duration) {
		// Calculate how far and how fast to scroll
		var startLocation = window.pageYOffset;
		var endLocation = document.body.offsetTop;
		var distance = endLocation - startLocation;
		var increments = distance/(duration/16);
		var stopAnimation;

		// Scroll the page by an increment, and check if it's time to stop
		var animateScroll = function () {
			window.scrollBy(0, increments);
			stopAnimation();
		};

		// Stop animation when you reach the anchor OR the top of the page
		stopAnimation = function () {
			var travelled = window.pageYOffset;
			if ( travelled <= (endLocation || 0) ) {
				clearInterval(runAnimation);
				document.activeElement.blur();
			}
		};

		// Loop the animation function
		var runAnimation = setInterval(animateScroll, 16);
	};

	if ( scrollBtn ) {
		// Show the button when scrolling down.
		window.addEventListener( 'scroll', trackScroll );

		// Scroll back to top when clicked.
		scrollBtn.addEventListener( 'click', function( e ) {
			e.preventDefault();
			smoothScroll( document.body, scrollBtn.getAttribute( 'data-scroll-speed' ) || 400 );
		}, false );
	}

}() );
