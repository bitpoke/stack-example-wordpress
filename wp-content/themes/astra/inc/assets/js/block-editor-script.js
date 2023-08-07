
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

	wp.data.subscribe(function () {
		setTimeout( function () {
			// Compatibility for updating layout in editor with direct reflection.
			const contentLayout = ( undefined !== wp.data.select( 'core/editor' ) && null !== wp.data.select( 'core/editor' ) && undefined !== wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' ) && wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-content-layout'] ) ? wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-content-layout'] : 'default',
				bodyClass = document.querySelector('body');

			switch( contentLayout ) {
				case 'boxed-container':
					bodyClass.classList.add('ast-separate-container' , 'ast-two-container');
					bodyClass.classList.remove('ast-page-builder-template' , 'ast-plain-container', 'ast-narrow-container');
				break;
				case 'content-boxed-container':
					bodyClass.classList.add('ast-separate-container');
					bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-plain-container', 'ast-narrow-container');
				break;
				case 'plain-container':
					bodyClass.classList.add('ast-plain-container');
					bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-separate-container', 'ast-narrow-container');
				break;
				case 'page-builder':
					bodyClass.classList.add('ast-page-builder-template');
					bodyClass.classList.remove('ast-two-container' , 'ast-plain-container' , 'ast-separate-container', 'ast-narrow-container');
				break;
				case 'narrow-container':
					bodyClass.classList.add('ast-narrow-container');
					bodyClass.classList.remove('ast-two-container' , 'ast-plain-container' , 'ast-separate-container', 'ast-page-builder-template');
				break;
				case 'default':
					if( bodyClass.classList.contains( 'ast-default-layout-boxed-container' ) ) {
						bodyClass.classList.add('ast-separate-container' , 'ast-two-container');
						bodyClass.classList.remove('ast-page-builder-template' , 'ast-plain-container', 'ast-narrow-container');
					} else if( bodyClass.classList.contains( 'ast-default-layout-content-boxed-container' ) ) {
						bodyClass.classList.add('ast-separate-container');
						bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-plain-container', 'ast-narrow-container');
					} else if( bodyClass.classList.contains( 'ast-default-layout-page-builder' ) ) {
						bodyClass.classList.add('ast-page-builder-template');
						bodyClass.classList.remove('ast-two-container' , 'ast-plain-container' , 'ast-separate-container', 'ast-narrow-container');
					} else if( bodyClass.classList.contains( 'ast-default-layout-plain-container' ) ) {
						bodyClass.classList.add('ast-plain-container');
						bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-separate-container', 'ast-narrow-container');
					} else if( bodyClass.classList.contains( 'ast-default-layout-narrow-container' ) ) {
						bodyClass.classList.add('ast-narrow-container');
						bodyClass.classList.remove('ast-two-container' , 'ast-page-builder-template' , 'ast-separate-container', 'ast-plain-container');
					}
				break;
			}

			const editorStylesWrapper = document.querySelector( '.editor-styles-wrapper' );

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

			// Title visibility with new editor compatibility update.
			var titleVisibility = document.querySelector( '.title-visibility' ),
				titleBlock = document.querySelector( '.edit-post-visual-editor__post-title-wrapper' );
			if( null === titleVisibility && null !== titleBlock ) {
				var titleVisibilityTrigger = '';
				if( 'disabled' === wp.data.select( 'core/editor' ).getEditedPostAttribute( 'meta' )['site-post-title'] ) {
					titleVisibilityTrigger = '<span class="dashicons dashicons-hidden title-visibility" data-tooltip="Enable Title"></span>';
					titleBlock.classList.toggle( 'invisible' );
				} else {
					titleVisibilityTrigger = '<span class="dashicons dashicons-visibility title-visibility" data-tooltip="Disable Title"></span>';
				}

				titleBlock.insertAdjacentHTML( 'beforeend', titleVisibilityTrigger );
				document.querySelector( '.title-visibility' ).addEventListener( 'click', function() {
					var titleBlock = document.querySelector( '.edit-post-visual-editor__post-title-wrapper' );
					titleBlock.classList.toggle( 'invisible' );

					if( this.classList.contains( 'dashicons-hidden' ) ) {
						this.classList.add( 'dashicons-visibility' );
						this.classList.remove( 'dashicons-hidden' );
						this.dataset.tooltip = 'Disable Title';
						wp.data.dispatch( 'core/editor' ).editPost(
							{
								meta: {
									'site-post-title': '',
								}
							}
						);
					} else {
						this.classList.add( 'dashicons-hidden' );
						this.classList.remove( 'dashicons-visibility' );
						this.dataset.tooltip = 'Enable Title';
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

			// Show post/page title wrapper outline & eye icon only when clicked.
			const titleInput     = document.querySelector('.editor-post-title__input');
			const visibilityIcon = document.querySelector('.title-visibility');
			if( null != titleInput && null != visibilityIcon ) {
				document.addEventListener('click', function (event){
					if( ! titleBlock.contains( event.target ) ){
						visibilityIcon.classList.remove('ast-show-visibility-icon');
						titleInput.classList.remove('ast-show-editor-title-outline');
					}
				});
				document.addEventListener('visibilitychange', function (){
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
if(true === blockCssMode){
	var blockCss = document.getElementById('astra-block-editor-styles-css');
	var inlineCss = document.getElementById('astra-block-editor-styles-inline-css');

}else {
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
			if(true === blockCssMode){
				var styleTagId = 'astra-block-editor-styles-inline-css';
				var styleTagBlockId = 'astra-block-editor-styles-css';

			} else{
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
