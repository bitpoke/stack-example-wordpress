"use strict"

import { registerPlugin } from '@wordpress/plugins';
import MetaSettings from './settings';

import {PanelRow, CheckboxControl} from "@wordpress/components";
import {withSelect, withDispatch} from "@wordpress/data";
import {Component, createElement, Fragment} from "@wordpress/element";
import {compose} from "@wordpress/compose";
import {addFilter} from "@wordpress/hooks";

const { __ } = wp.i18n;

if( astMetaParams.register_astra_metabox ) {
	registerPlugin( 'astra-theme-layout', { render: MetaSettings } );
	class HideFeaturedImage extends Component {
		render() {
			const {
				meta,
				setMetaFieldValue
			} = this.props;

			let toggleValue = ( 'disabled' === meta['ast-featured-img'] ) ? true : false;

			return (
				<>
					<PanelRow>
						<CheckboxControl
							label = { __( 'Show featured image in the posts lists only, but hide it in the single post view.', 'astra' ) }
							className = { 'ast-featured-img' }
							checked={ toggleValue }
							onChange={ ( val ) => {
								val = ( true === val ) ? 'disabled' : '';
								setMetaFieldValue( val, 'ast-featured-img' ) }
							}
						/>
					</PanelRow>
				</>
			)
		}
	}

	const composedHideFeaturedImage = compose( [
		withSelect( ( select ) => {
			const postMeta = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
			const oldPostMeta = select( 'core/editor' ).getCurrentPostAttribute( 'meta' );
			return {
				meta: { ...oldPostMeta, ...postMeta },
				oldMeta: oldPostMeta,
			};
		} ),
		withDispatch( ( dispatch ) => ( {
			setMetaFieldValue: ( value, field ) => dispatch( 'core/editor' ).editPost(
				{ meta: { [ field ]: value } }
			),
		} ) ),
	] ) ( HideFeaturedImage );

	const wrapDisablePostFeaturedImageMeta = function ( OriginalComponent ) {
		return function (props) {
			return (
				createElement(
					Fragment,
					{},
					null,
					createElement(
						OriginalComponent,
						props
					),
					createElement(
						composedHideFeaturedImage
					)
				)
			);
		}
	};

	/**
	 * PostFeaturedImage is a React component used to render the Post Featured Image selection tool.
	 *
	 * @see https://github.com/WordPress/gutenberg/blob/trunk/packages/editor/src/components/post-featured-image/README.md
	 */
	addFilter(
		'editor.PostFeaturedImage',
		'astra/disable-featured-image',
		wrapDisablePostFeaturedImageMeta
	);
}
