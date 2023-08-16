
window.addEventListener( 'load', function(e) {
	astra_onload_function();
});

function astra_onload_function() {

	/* Do things after DOM has fully loaded */

	var astraMetaBox = document.querySelector( '#astra_settings_meta_box' );
	if( astraMetaBox != null ){

		var titleCheckbox = document.getElementById('site-post-title');

		if( null === titleCheckbox ) {
			titleCheckbox = document.querySelector('.site-post-title input');
		}

		if( null !== titleCheckbox ) {
			titleCheckbox.addEventListener('change',function() {
				var titleBlock = document.querySelector('.editor-post-title__block');
				if( null !== titleBlock ) {
					if( titleCheckbox.checked ){
						titleBlock.style.opacity = '0.2';
					} else {
						titleBlock.style.opacity = '1.0';
					}
				}
			});
		}
	}

	let titleVisibility = document.querySelector( '.title-visibility' ),
		titleBlock = document.querySelector( '.edit-post-visual-editor__post-title-wrapper' ),
		editorDocument = document,
		postTitleOption = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-post-title'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-post-title'] : '';

	if ( astraColors.ast_wp_version_higher_6_3 ) {
		let desktopPreview = document.getElementsByClassName('is-desktop-preview'),
			tabletPreview = document.getElementsByClassName('is-tablet-preview'),
			mobilePreview = document.getElementsByClassName('is-mobile-preview'),
			devicePreview = desktopPreview[0];

		if ( tabletPreview.length > 0 ) {
			devicePreview = tabletPreview[0];
		} else if ( mobilePreview.length > 0 ) {
			devicePreview = mobilePreview[0];
		}

		let iframe = undefined !== devicePreview ? devicePreview.getElementsByTagName('iframe')[0] : undefined;
		if ( iframe && devicePreview.querySelector('iframe') !== null ) {
			editorDocument = iframe.contentWindow.document || iframe.contentDocument;
		}

		titleVisibility = editorDocument.querySelector( '.title-visibility' );
		titleBlock = editorDocument.querySelector( '.edit-post-visual-editor__post-title-wrapper' );
	}

	if( null !== titleBlock ) {
		let titleVisibilityTrigger = '<span class="ast-title title-visibility" data-tooltip="Disable Title"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path></svg> </span>';

		if ( 'disabled' === postTitleOption ) {
			titleVisibilityTrigger = '<span class="ast-title title-visibility" data-tooltip="Enable Title"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path></svg> </span>';
		}

		if ( null === titleVisibility ) {
			titleBlock.insertAdjacentHTML( 'beforeend', titleVisibilityTrigger );
		}

		let titleVisibilityTriggerElement = editorDocument.querySelector( '.title-visibility' ),
			titleVisibilityWrapper = editorDocument.querySelector( '.edit-post-visual-editor__post-title-wrapper' );

		if( 'disabled' === postTitleOption && ! titleVisibilityWrapper.classList.contains( 'invisible' ) ) {
			titleVisibilityWrapper.classList.add( 'invisible' );
		} else {
			titleVisibilityWrapper.classList.remove( 'invisible' );
		}

		titleVisibilityTriggerElement.addEventListener("click", function() {
			let metaTitleOptions = postTitleOption || '';
			if ( this.parentNode.classList.contains( 'invisible' ) && ( 'disabled' === metaTitleOptions || '' === metaTitleOptions ) ) {
				this.parentNode.classList.remove( 'invisible' );
				this.dataset.tooltip = 'Disable Title';
				titleVisibilityTriggerElement.innerHTML = '';
				titleVisibilityTriggerElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"></path></svg>';

				wp.data.dispatch( 'core/editor' ).editPost(
					{
						meta: {
							'site-post-title': '',
						}
					}
				);
			} else {
				this.parentNode.classList.add( 'invisible' );
				this.dataset.tooltip = 'Enable Title';
				titleVisibilityTriggerElement.innerHTML = '';
				titleVisibilityTriggerElement.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"></path></svg>';

				wp.data.dispatch( 'core/editor' ).editPost(
					{
						meta: {
							'site-post-title': 'disabled',
						}
					}
				);
			}
		});
	}

	wp.data.subscribe(function () {
		setTimeout( function () {
			// Title visibility with new editor compatibility update.
			var titleBlock = document.querySelector( '.edit-post-visual-editor__post-title-wrapper' ),
				editorDocument = document;

			if ( astraColors.ast_wp_version_higher_6_3 ) {
				let desktopPreview = document.getElementsByClassName('is-desktop-preview'),
					tabletPreview = document.getElementsByClassName('is-tablet-preview'),
					mobilePreview = document.getElementsByClassName('is-mobile-preview'),
					devicePreview = desktopPreview[0];

				if ( tabletPreview.length > 0 ) {
					devicePreview = tabletPreview[0];
				} else if ( mobilePreview.length > 0 ) {
					devicePreview = mobilePreview[0];
				}

				let iframe = undefined !== devicePreview ? devicePreview.getElementsByTagName('iframe')[0] : undefined;
				if ( iframe && devicePreview.querySelector('iframe') !== null ) {
					editorDocument = iframe.contentWindow.document || iframe.contentDocument;
				}

				titleBlock = editorDocument.querySelector( '.edit-post-visual-editor__post-title-wrapper' );
			}

			// Compatibility for updating layout in editor with direct reflection.
			const contentLayout = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['ast-site-content-layout'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['ast-site-content-layout'] : 'default',
				bodyClass = astraColors.ast_wp_version_higher_6_3 ? editorDocument.querySelector('html') : document.querySelector('body');
			const contentStyle = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-content-style'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-content-style'] : 'default';
			const sidebarStyle = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-sidebar-style'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-sidebar-style'] : 'default';
			const sidebarLayout = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-sidebar-layout'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-sidebar-layout'] : 'default';
			const applyContainerLayoutClasses = (layout) => {
				switch (layout) {
					case "plain-container":
						bodyClass.classList.add("ast-plain-container");
						bodyClass.classList.remove(
							"ast-two-container",
							"ast-page-builder-template",
							"ast-separate-container",
							"ast-narrow-container"
						);
						break;
					case "content-boxed-container":
						bodyClass.classList.add("ast-separate-container");
						bodyClass.classList.remove(
							"ast-two-container",
							"ast-page-builder-template",
							"ast-plain-container",
							"ast-narrow-container"
						);
						break;
					case "boxed-container":
						bodyClass.classList.add(
							"ast-separate-container",
							"ast-two-container"
						);
						bodyClass.classList.remove(
							"ast-page-builder-template",
							"ast-plain-container",
							"ast-narrow-container"
						);
						break;
					case "page-builder-template":
						bodyClass.classList.add("ast-page-builder-template");
						bodyClass.classList.remove(
							"ast-two-container",
							"ast-plain-container",
							"ast-separate-container",
							"ast-narrow-container"
						);
						break;
					case "narrow-container":
						bodyClass.classList.add("ast-narrow-container");
						bodyClass.classList.remove(
							"ast-two-container",
							"ast-plain-container",
							"ast-separate-container",
							"ast-page-builder-template"
							);
						break;
					default:
						break;
				}
			};

			switch( contentLayout ) {
			case 'normal-width-container':
				applyContainerLayoutClasses( 'plain-container' );
			break;
			case 'narrow-width-container':
				applyContainerLayoutClasses( 'narrow-container' );
			break;
			case 'full-width-container':
				applyContainerLayoutClasses( 'page-builder-template' );
			break;
			case 'default':
				if( bodyClass.classList.contains( 'ast-default-layout-boxed-container' ) ) {
					applyContainerLayoutClasses( 'boxed-container' );
				}
				else if( bodyClass.classList.contains( 'ast-default-layout-content-boxed-container' ) ) {
					applyContainerLayoutClasses( 'content-boxed-container' );
				} else if( bodyClass.classList.contains( 'ast-default-layout-page-builder' ) ) {
					applyContainerLayoutClasses( 'page-builder-template' );
				} else if( bodyClass.classList.contains( 'ast-default-layout-plain-container' ) ) {
					applyContainerLayoutClasses( 'plain-container' );
				} else if( bodyClass.classList.contains( 'ast-default-layout-narrow-container' ) ) {
					applyContainerLayoutClasses( 'narrow-container' );
				}
			break;
		}

		const is_default_boxed         = bodyClass.classList.contains( 'ast-default-layout-boxed-container' );
		const is_default_content_boxed = bodyClass.classList.contains( 'ast-default-layout-content-boxed-container' );
		const is_default_normal        = bodyClass.classList.contains( 'ast-default-layout-plain-container' );
		const is_default_normal_width  = ( 'default' === contentLayout && ( is_default_boxed || is_default_content_boxed || is_default_normal ) );
		const is_content_style_boxed   = bodyClass.classList.contains( 'ast-default-content-boxed' );
		const is_sidebar_style_boxed   = bodyClass.classList.contains( 'ast-default-sidebar-boxed' );

		if ( 'normal-width-container' === contentLayout || is_default_normal_width ) {
			switch ( contentStyle ) {
				case 'boxed':
					applyContainerLayoutClasses( 'boxed-container' );
					break;
				case 'unboxed':
					applyContainerLayoutClasses( 'plain-container' );
				break;
				default:
					if ( is_content_style_boxed ) {
						applyContainerLayoutClasses( 'boxed-container' );
					}
					break;
			}

			const is_sidebar_default_enabled = 'default' === sidebarLayout && ( ! bodyClass.classList.contains( 'ast-sidebar-default-no-sidebar' ) );
			if( ( 'default' !== sidebarLayout && 'no-sidebar' !== sidebarLayout || is_sidebar_default_enabled ) ) {
				switch ( sidebarStyle ) {
					case 'boxed':
						applyContainerLayoutClasses( 'boxed-container' );
						break;
					case 'unboxed':
						applyContainerLayoutClasses( 'content-boxed-container' );
						if ( 'unboxed' === contentStyle || 'default' === contentStyle && ! is_content_style_boxed ) {
							applyContainerLayoutClasses( 'plain-container' );
						}
						break;
					default:
						if ( 'unboxed' === contentStyle && ! is_sidebar_style_boxed ) {
							applyContainerLayoutClasses( 'plain-container' );
						}
						else if ( 'default' === contentStyle && ! is_sidebar_style_boxed && ! is_content_style_boxed ) {
							applyContainerLayoutClasses( 'plain-container' );
						}
						else if ( is_sidebar_style_boxed ) {
							applyContainerLayoutClasses( 'boxed-container' );
						}
						else if ( ! is_sidebar_style_boxed ) {
							applyContainerLayoutClasses( 'content-boxed-container' );
						}
						break;
				}
			}
		}

			const editorStylesWrapper = editorDocument.querySelector( '.editor-styles-wrapper' );

			if( null !== editorStylesWrapper ) {
				const editorStylesWrapperWidth = parseInt( editorStylesWrapper.offsetWidth )
				if( editorStylesWrapperWidth < 1350 ) {
					editorStylesWrapper.classList.remove( 'ast-stacked-title-visibility' );
					editorStylesWrapper.classList.add( 'ast-stacked-title-visibility' );
				} else {
					editorStylesWrapper.classList.remove( 'ast-stacked-title-visibility' );
				}
			}

			/**
			 * In WP-5.9 block editor comes up with color palette showing color-code canvas, but with theme var() CSS its appearing directly as it is. So updated them on wp.data event.
			 */
			const customColorPickerButtons = document.querySelectorAll( '.components-color-palette__custom-color-value' );

			for ( let btnCount = 0; btnCount < customColorPickerButtons.length; btnCount++ ) {
				let colorCode = customColorPickerButtons[btnCount].innerText,
					transformedCode = colorCode.toLowerCase();
				if ( colorCode.indexOf( 'VAR(--AST-GLOBAL-COLOR' ) > -1 ) {
					customColorPickerButtons[btnCount].innerHTML = astraColors[ transformedCode ];
				}
			}

			// Show post/page title wrapper outline & eye icon only when clicked.
			const titleInput     = editorDocument.querySelector('.editor-post-title__input');
			const visibilityIcon = editorDocument.querySelector('.title-visibility');
			if( null != titleInput && null != visibilityIcon ) {
				if ( ! astraColors.ast_wp_version_higher_6_3 ) {
					editorDocument.addEventListener('click', function (event){
						if( ! titleBlock.contains( event.target ) ){
							visibilityIcon.classList.remove('ast-show-visibility-icon');
							titleInput.classList.remove('ast-show-editor-title-outline');
						}
					});
				}
				editorDocument.addEventListener('visibilitychange', function (){
						visibilityIcon.classList.remove('ast-show-visibility-icon');
						titleInput.classList.remove('ast-show-editor-title-outline');
				});
				titleBlock.addEventListener('focusout', function (){
					visibilityIcon.classList.remove('ast-show-visibility-icon');
					titleInput.classList.remove('ast-show-editor-title-outline');
				});
				titleBlock.addEventListener('click', function (){
					visibilityIcon.classList.add('ast-show-visibility-icon');
					titleInput.classList.add('ast-show-editor-title-outline');
				});
				titleInput.addEventListener('input', function (){
					visibilityIcon.classList.add('ast-show-visibility-icon');
					this.classList.add('ast-show-editor-title-outline');
				});
			}

			var responsivePreview = document.querySelectorAll( '.is-tablet-preview, .is-mobile-preview' );
			if( responsivePreview.length ) {
				document.body.classList.add( 'responsive-enabled' );
			} else {
				document.body.classList.remove( 'responsive-enabled' );
			}

			// Adding 'inherit-container-width' width to Group block externally.
			let postBlocks = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getCurrentPost() && undefined !== wp.data.select( 'core/block-editor' ).getBlocks() ) ? wp.data.select( 'core/block-editor' ).getBlocks() : false,
				groupBlocks = document.querySelectorAll( '.block-editor-block-list__layout.is-root-container > .wp-block-group' );
			if( postBlocks && groupBlocks ) {
				for ( let blockNum = 0; blockNum < postBlocks.length; blockNum++ ) {
					if( 'core/group' === postBlocks[blockNum].name && undefined !== postBlocks[blockNum].attributes && undefined !== postBlocks[blockNum].attributes.layout && undefined !== postBlocks[blockNum].attributes.layout.inherit ) {
						if( undefined === groupBlocks[blockNum] ) {
							return;
						}
						if( ! postBlocks[blockNum].attributes.layout.inherit ) {
							groupBlocks[blockNum].classList.remove( 'inherit-container-width' );
						}
						if( postBlocks[blockNum].attributes.layout.inherit && ! groupBlocks[blockNum].classList.contains( 'inherit-container-width' ) ) {
							groupBlocks[blockNum].classList.add( 'inherit-container-width' );
						}
					}
				}
			}

		}, 1 );
	});
}

document.body.addEventListener('mousedown', function () {
	var blockCssMode = document.querySelector('body').classList.contains('ast-block-legacy')
	var fontCss = document.getElementById('astra-google-fonts-css');
	if( true === blockCssMode ){
		var blockCss = document.getElementById('astra-block-editor-styles-css');
		var inlineCss = document.getElementById('astra-block-editor-styles-inline-css');
	} else {
		var blockCss = document.getElementById('astra-wp-editor-styles-css');
		var inlineCss = document.getElementById('astra-wp-editor-styles-inline-css');
	}


	var blockFixCss = null !== blockCss ? blockCss.cloneNode(true) : null;
	var blockInlineCss = null !== inlineCss ?  inlineCss.cloneNode(true) : null;
	var blockfontCss = null !== fontCss ? fontCss.cloneNode(true) : null;

	setTimeout( function() {

		let tabletPreview = document.getElementsByClassName('is-tablet-preview');
		let mobilePreview = document.getElementsByClassName('is-mobile-preview');

		if (0 !== tabletPreview.length || 0 !== mobilePreview.length) {
			var googleFontId = 'astra-google-fonts-css';
			if( true === blockCssMode ){
				var styleTagId = 'astra-block-editor-styles-inline-css';
				var styleTagBlockId = 'astra-block-editor-styles-css';
			} else {
				var styleTagId = 'astra-wp-editor-styles-inline-css';
				var styleTagBlockId = 'astra-wp-editor-styles-css';
			}
			var styleTagId = 'astra-block-editor-styles-inline-css';
			var styleTagBlockId = 'astra-block-editor-styles-css';
			var googleFontId = 'astra-google-fonts-css';
			let preview = tabletPreview[0] || mobilePreview[0];

				let iframe = preview.getElementsByTagName('iframe')[0];
				let iframeDocument = iframe.contentWindow.document || iframe.contentDocument;

				let element = iframeDocument.getElementById(
					styleTagId
				);
				let elementBlock = iframeDocument.getElementById(
					styleTagBlockId
				);
				let elementGoogleFont = iframeDocument.getElementById(
					googleFontId
				);
				if ( (null === element || undefined === element)) {

						iframeDocument.head.appendChild( blockInlineCss );
				}
				if ( (null === elementBlock || undefined === elementBlock )) {

					iframeDocument.head.appendChild( blockFixCss );
				}
				if ( (null === elementGoogleFont || undefined === elementGoogleFont ) && null !== fontCss) {

					iframeDocument.head.appendChild( blockfontCss );
				}

		}
	}, 1000);

});
