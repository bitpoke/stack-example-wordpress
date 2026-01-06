/**
 * Astra Command Palette Integration
 *
 * Registers Astra customizer panels with WordPress Command Palette.
 *
 * @package Astra
 * @since 4.11.18
 */

( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.data || ! wp.commands ) {
		return;
	}

	const { dispatch } = wp.data;
	const { store: commandsStore } = wp.commands;

	const config = window.astraCommandPalette || {};
	const customizerUrl = config.customizerUrl || '';
	const panels = config.panels || [];
	const iconUrl = config.iconUrl || '';

	if ( ! customizerUrl || panels.length === 0 ) {
		return;
	}

	const { createElement } = wp.element;
	const astraIcon = iconUrl ? createElement(
		'img',
		{
			src: iconUrl,
			alt: 'Astra',
			width: 20,
			height: 20,
		}
	) : null;

	// Function to register commands.
	function registerAstraCommands() {
		panels.forEach( function ( panel ) {
			let url = customizerUrl;
			if ( panel.type === 'panel' ) {
				url += '?autofocus[panel]=' + panel.id;
			} else if ( panel.type === 'section' ) {
				url += '?autofocus[section]=' + panel.id;
			}

			try {
				// Register the command.
				dispatch( commandsStore ).registerCommand( {
					name: panel.name,
					label: panel.label,
					searchLabel: panel.searchLabel || panel.label,
					icon: astraIcon,
					callback: function () {
						window.location.href = url;
						},
				} );
			} catch ( error ) {
				console.error( 'Astra Command Palette: Failed to register', panel.name, error );
			}
		} );
	}

	// Wait for the editor to be ready before registering commands.
	if ( wp.domReady ) {
		wp.domReady( registerAstraCommands );
	} else {
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', registerAstraCommands );
		} else {
			registerAstraCommands();
		}
	}
} )( window.wp );
