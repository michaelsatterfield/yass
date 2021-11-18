/**
 * File navigation.js.
 */
( function() {
	'use strict';
	
	// Vars.
	var navToggles = document.querySelectorAll( '.menu-toggle' ),
		dropdownToggle = document.querySelectorAll( 'nav .dropdown-menu-toggle' );

	// Function to open mobile menu.
	var toggleNav = function( e, _this ) {
		if ( ! _this ) {
			var _this = this;
		}

		var container = document.getElementById( _this.closest( 'nav' ).getAttribute( 'id' ) );

		if ( ! container ) {
			return;
		}

		var nav = container.getElementsByTagName( 'ul' )[0];

		if ( container.classList.contains( 'toggled' ) ) {
			container.classList.remove( 'toggled' );
			nav.setAttribute( 'aria-hidden', 'true' );
			_this.setAttribute( 'aria-expanded', 'false' );

			disableDropdownArrows( nav );
		} else {
			container.classList.add( 'toggled' );
			nav.setAttribute( 'aria-hidden', 'false' );
			_this.setAttribute( 'aria-expanded', 'true' );

			enableDropdownArrows( nav );
		}
	}

	for ( var i = 0; i < navToggles.length; i++ ) {
		navToggles[i].addEventListener( 'click', toggleNav, false );
	}

	// Functions to open sub menus on mobile menu.
	var enableDropdownArrows = function( nav ) {
		var dropdownItems = nav.querySelectorAll( 'li.menu-item-has-children' );

		for ( var i = 0; i < dropdownItems.length; i++ ) {
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).setAttribute( 'tabindex', '0' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).setAttribute( 'role', 'button' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).setAttribute( 'aria-expanded', 'false' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).setAttribute( 'aria-label', olyMenu.openSubMenuLabel );
		}
	};

	var disableDropdownArrows = function( nav ) {
		var dropdownItems = nav.querySelectorAll( 'li.menu-item-has-children' );

		for ( var i = 0; i < dropdownItems.length; i++ ) {
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).removeAttribute( 'tabindex' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).setAttribute( 'role', 'presentation' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).removeAttribute( 'aria-expanded' );
			dropdownItems[i].querySelector( '.dropdown-menu-toggle' ).removeAttribute( 'aria-label' );
		}
	};

	var setDropdownArrowAttributes = function( arrow ) {
		if ( 'false' === arrow.getAttribute( 'aria-expanded' ) || ! arrow.getAttribute( 'aria-expanded' ) ) {
			arrow.setAttribute( 'aria-expanded', 'true' );
			arrow.setAttribute( 'aria-label', olyMenu.closeSubMenuLabel );
		} else {
			arrow.setAttribute( 'aria-expanded', 'false' );
			arrow.setAttribute( 'aria-label', olyMenu.openSubMenuLabel );
		}
	};

	var toggleSubNav = function( e, _this ) {
		if ( ! _this ) {
			var _this = this;
		}

		if ( _this.closest( 'nav' ).classList.contains( 'toggled' ) ) {
			e.preventDefault();
			var closestLi = _this.closest( 'li' );

			setDropdownArrowAttributes( closestLi.querySelector( '.dropdown-menu-toggle' ) );

			for ( var i = 0; i < closestLi.length; i++ ) {
				if ( closestLi[i].classList.contains( 'sfHover' ) ) {
					closestLi[i].classList.remove( 'sfHover' );
					closestLi[i].querySelector( '.toggled-on' ).classList.remove( 'sub-open' );
					setDropdownArrowAttributes( closestLi[i].querySelector( '.dropdown-menu-toggle' ) );
				}
			}

			closestLi.classList.toggle( 'sfHover' );
			closestLi.querySelector( '.sub-menu' ).classList.toggle( 'sub-open' );
		}

		e.stopPropagation();
	}

	for ( var i = 0; i < dropdownToggle.length; i++ ) {
		dropdownToggle[i].addEventListener( 'click', toggleSubNav, false );
	}

	// Remove mobile menu classes on window resize.
	var checkMobile = function() {
		var openedMobileMenus = document.querySelectorAll( '.toggled' );

		for ( var i = 0; i < openedMobileMenus.length; i++ ) {
			var menuToggle = openedMobileMenus[i].querySelector( '.menu-toggle' );

			if ( menuToggle && menuToggle.offsetParent === null ) {
				if ( openedMobileMenus[i].classList.contains( 'toggled' ) ) {
					// Navigation is toggled, but .menu-toggle isn't visible on the page (display: none).
					var closestNav = openedMobileMenus[i].getElementsByTagName( 'ul' )[ 0 ],
						closestNavItems = closestNav.getElementsByTagName( 'li' ),
						closestSubMenus = closestNav.getElementsByTagName( 'ul' );

					document.activeElement.blur();
					openedMobileMenus[i].classList.remove( 'toggled' );

					menuToggle.setAttribute( 'aria-expanded', 'false' );

					for ( var li = 0; li < closestNavItems.length; li++ ) {
						closestNavItems[li].classList.remove( 'sfHover' );
					}

					for ( var sm = 0; sm < closestSubMenus.length; sm++ ) {
						closestSubMenus[sm].classList.remove( 'sub-open' );
					}

					if ( closestNav ) {
						closestNav.removeAttribute( 'aria-hidden' );
					}

					disableDropdownArrows( openedMobileMenus[i] );
				}
			}
		}
	}
	window.addEventListener( 'resize', checkMobile, false );
	window.addEventListener( 'orientationchange', checkMobile, false );

}() );
