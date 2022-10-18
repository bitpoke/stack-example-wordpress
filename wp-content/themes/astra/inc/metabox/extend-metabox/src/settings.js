/**
 * Meta Options build.
 */
import { useState } from 'react';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import AstCheckboxControl from './ast-checkbox.js';
import AstRadioImageControl from './ast-radio-image.js';
import AstSelectorControl from './ast-selector.js';
import svgIcons from '../../../../assets/svg/svgs.json';
import { SelectControl, PanelBody, Modal } from '@wordpress/components';
import parse from 'html-react-parser';
const { __ } = wp.i18n;

const MetaSettings = props => {

	const modalIcon = parse( svgIcons['meta-popup-icon'] );
	const brandIcon = astMetaParams.isWhiteLabelled ? '' : parse( svgIcons['astra-brand-icon'] );

    const [ isOpen, setOpen ] = useState( false );

    const openModal = () => setOpen( true );
    const closeModal = () => setOpen( false );

	// Adjust spacing & borders for table.
	const topTableSpacing = <tr className="ast-extra-spacing"><td className="ast-border"></td><td></td></tr>;
	const bottomTableSpacing = <tr className="ast-extra-spacing ast-extra-spacing-bottom"><td className="ast-border"></td><td></td></tr>;

	const icon = parse( svgIcons['astra-meta-settings'] );
	const sidebarOptions = Object.entries( astMetaParams.sidebar_options ).map( ( [ key, name ] ) => {
		return ( { label: name, value: key } );
	} );

	const contentLayoutOptions = Object.entries( astMetaParams.content_layout ).map( ( [ key, name ] ) => {
		return ( { label: name, value: key } );
	} );

	// Taransparent and Sticky Header Options.
	const headerOptions = Object.entries( astMetaParams.header_options ).map( ( [ key, name ] ) => {
		return ( { label: name, value: key } );
	} );

	// Page header optins.
	const pageHeaderOptions = Object.entries( astMetaParams.page_header_options ).map( ( [ key, name ] ) => {
		return ( { label: name, value: key } );
	} );

	// Checkbox control
	const disableSections = Object.entries( astMetaParams.disable_sections ).map( ( [ key, value ] ) => {
		let sectionValue = ( 'disabled' === props.meta[value['key']] ) ? true : false;
		return (
		<AstCheckboxControl
			label = { value['label'] }
			value = { sectionValue }
			key = { key }
			name = { value['key'] }
			onChange = { ( val ) => {
				props.setMetaFieldValue( val, value['key'] );
			} }
		/>);
	});
	const headers_meta_options = Object.entries( astMetaParams.headers_meta_options ).map( ( [ key, value ] ) => {
		let sectionValue = ( 'disabled' === props.meta[value['key']] ) ? true : false;
		return (
		<AstCheckboxControl
			label = { value['label'] }
			value = { sectionValue }
			key = { key }
			name = { value['key'] }
			onChange = { ( val ) => {
				props.setMetaFieldValue( val, value['key'] );
			} }
		/>);
	});

	// Checkbox control
	const stickyHeadderOptions = Object.entries( astMetaParams.sticky_header_options ).map( ( [ key, value ] ) => {
		let stickyValue =  ( 'disabled' === props.meta[value['key']] ) ? true : false;
		return (
		<AstCheckboxControl
			label = { value['label'] }
			value = { stickyValue }
			key = { key }
			name = { value['key'] }
			onChange = { ( val ) => {
				props.setMetaFieldValue( val, value['key'] );
			} }
		/>);
	});

	return (
		<>
			{/* Meta settings icon */}
			<PluginSidebarMoreMenuItem
				target="theme-meta-panel"
				icon={ icon }
			>
				{ astMetaParams.title }
			</PluginSidebarMoreMenuItem>

			{/* Meta seetings popup area */}
				<PluginSidebar
				isPinnable={ true }
				icon={ icon }
				name="theme-meta-panel"
				title={ astMetaParams.title }
			>

				<div className="ast-sidebar-container components-panel__body is-opened" id="astra_settings_meta_box">

					{/* Content Layout Setting */}
					<PanelBody
						title={ __( 'Content Layout', 'astra' ) }
						initialOpen={ true }
					>
						<div className="ast-sidebar-layout-meta-wrap components-base-control__field">
							<AstRadioImageControl
								metavalue = { ( undefined !== props.meta['site-content-layout'] && ''!== props.meta['site-content-layout'] ? props.meta['site-content-layout'] : 'default' ) }
								choices = { contentLayoutOptions }
								id = { 'site-content-layout' }
								onChange={ ( val ) => {
									props.setMetaFieldValue( val, 'site-content-layout' );
								} }
							/>
						</div>
					</PanelBody>

					{/* Sidebar Setting */}
					<PanelBody
						title={ __( 'Sidebar', 'astra' ) }
						initialOpen={ false }
					>
						<div className="ast-sidebar-layout-meta-wrap components-base-control__field">
							<AstRadioImageControl
								metavalue = { ( undefined !== props.meta['site-sidebar-layout'] && ''!== props.meta['site-sidebar-layout'] ? props.meta['site-sidebar-layout'] : 'default' ) }
								choices = { sidebarOptions }
								id = { 'site-sidebar-layout' }
								onChange={ ( val ) => {
									props.setMetaFieldValue( val, 'site-sidebar-layout' );
								} }
							/>
						</div>
					</PanelBody>

					{/* Disable Section Setting */}
					<PanelBody
						title={ __( 'Disable Elements', 'astra' ) }
						initialOpen={ false }
					>
						<div className="ast-sidebar-layout-meta-wrap components-base-control__field">
							{ disableSections }
						</div>
					</PanelBody>

					{ ( undefined !== props.meta['ast-global-header-display'] && 'disabled' !== props.meta['ast-global-header-display'] ) &&
						<div className="ast-custom-layout-panel components-panel__body">
							<h2 className="components-panel__body-title">
								<button className="components-button components-panel__body-toggle" onClick = { openModal }>
									<span className="ast-title-container">
										<div className="ast-title"> { __( 'Advanced Settings', 'astra' ) }</div>
									</span>
									{modalIcon}
								</button>
							</h2>
						</div>
					}

					{/* Header related all settings */}
					{ isOpen && (
						<Modal
							title={ __( 'Advanced Settings', 'astra' ) }
							className = "ast-header-settings-modal"
							shouldCloseOnClickOutside = { false }
							onRequestClose={ closeModal }
							icon={ brandIcon }
						>
							<div className="ast-meta-settings-content">
								<table className="ast-meta-settings-hook-table widefat">
									<tbody>
										{ topTableSpacing }
										<tr className="ast-advanced-hook-row">
											<td className="ast-advanced-hook-row-heading">
												<label> { __( 'Header Rows', 'astra' ) }</label>
											</td>
											<td className="ast-advanced-hook-row-content">
												<section className="components-base-control__field">
													{/* Individual header settings. */}
													{ headers_meta_options }
												</section>
											</td>
										</tr>
										{ bottomTableSpacing }
										{ topTableSpacing }
										<tr className="ast-advanced-hook-row">
											<td className="ast-advanced-hook-row-heading">
												<label> { astMetaParams.transparent_header_title }</label>
											</td>
											<td className="ast-advanced-hook-row-content">
												<section>
													{/* Transparent Header Setting */}
													<div className="components-base-control__field">
														<AstSelectorControl
															metavalue = { ( undefined !== props.meta['theme-transparent-header-meta'] && ''!== props.meta['theme-transparent-header-meta'] ? props.meta['theme-transparent-header-meta'] : 'default' ) }
															choices = { headerOptions }
															id = { 'theme-transparent-header-meta' }
															onChange={ ( val ) => {
																props.setMetaFieldValue( val, 'theme-transparent-header-meta' );
															} }
														/>
													</div>
												</section>
											</td>
										</tr>
										{ bottomTableSpacing }
										{/* Sticky Header Setting */}
										{ 'disabled' !== props.meta['ast-main-header-display'] && astMetaParams.is_addon_activated && astMetaParams.sticky_addon_enabled &&
											<>
												{ topTableSpacing }
												<tr className="ast-advanced-hook-row">
													<td className="ast-advanced-hook-row-heading">
														<label> { astMetaParams.sticky_header_title }</label>
													</td>
													<td className="ast-advanced-hook-row-content">
														<section>
															<AstSelectorControl
																metavalue = { ( undefined !== props.meta['stick-header-meta'] && ''!== props.meta['stick-header-meta'] ? props.meta['stick-header-meta'] : 'default' ) }
																choices = { headerOptions }
																id = { 'stick-header-meta' }
																onChange={ ( val ) => {
																	props.setMetaFieldValue( val, 'stick-header-meta' );
																} }
															/>
														</section>
													</td>
												</tr>
												{ topTableSpacing }
											</>
										}
										{ astMetaParams.is_addon_activated && astMetaParams.sticky_addon_enabled && 'enabled' == props.meta['stick-header-meta'] &&
											<>
												<tr className="ast-advanced-hook-row">
													<td className="ast-advanced-hook-row-heading">
														<label> { astMetaParams.sticky_header_title }</label>
													</td>
													<td className="ast-advanced-hook-row-content">
														<section>
															<div className="ast-sticky-header-options components-base-control__field">
																{stickyHeadderOptions}
															</div>
														</section>
													</td>
												</tr>
												{ topTableSpacing }
											</>
										}
									</tbody>
								</table>
							</div>
							<div className="ast-cl-footer-container">
								<div className="ast-button-container">
									<span className="ast-cl-popup-notice">
										<i className='dashicons dashicons-warning'></i>
										{ __( 'Make sure to update your post for changes to take effect.', 'astra' ) } </span>
									<button className="button button-default" onClick= { closeModal }> { __( 'Return To Post', 'astra' ) }</button>
								</div>
							</div>
						</Modal>
					) }

					{/* Page Header Setting */}
					{ ( astMetaParams.is_bb_themer_layout && astMetaParams.is_addon_activated && astMetaParams.page_header_availability ) &&
						<PanelBody
							title={ astMetaParams.page_header_title }
							initialOpen={ false }
						>
							<div className="ast-sidebar-layout-meta-wrap components-base-control__field">
								<SelectControl
									value={ ( undefined !== props.meta['adv-header-id-meta'] && '' !== props.meta['adv-header-id-meta'] ) ? props.meta['adv-header-id-meta'] : '' }
									options={ pageHeaderOptions.reverse() }
									onChange={ ( val ) => {
										props.setMetaFieldValue( val, 'adv-header-id-meta' );
									} }
								/>
							</div>
							<br/>
							<p className='description'>
								{ __( 'If you would like to apply custom header for this page, select the one from the list above. Page headers can be created and customized from ', 'astra' ) }
								<a href={ astMetaParams.page_header_edit_link } target='__blank'>
									{ __( 'here.', 'astra' ) }
								</a>
							</p>
						</PanelBody>
					}

				</div>
			</PluginSidebar>
		</>
	);
}

export default compose(
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
)( MetaSettings );
