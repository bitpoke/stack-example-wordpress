/**
 * Functionality related to WP Crontrol.
 */

document.addEventListener( 'DOMContentLoaded', () => {
	const checkCustom = () => {
		document.getElementById( 'crontrol_next_run_date_local_custom' ).checked = true;
	};

	const customDateElement = document.getElementById( 'crontrol_next_run_date_local_custom_date' );
	const customTimeElement = document.getElementById( 'crontrol_next_run_date_local_custom_time' );
	const newCronElement = document.querySelector( 'input[value="new_cron"]' );
	const newPHPCronElement = document.querySelector( 'input[value="new_php_cron"]' );
	const hookCodeElement = document.getElementById( 'crontrol_hookcode' );
	const hookNameElement = document.getElementById( 'crontrol_hookname' );
	const editEventElement = document.querySelector( '.crontrol-edit-event' );

	customDateElement && customDateElement.addEventListener( 'change', checkCustom );
	customTimeElement && customTimeElement.addEventListener( 'change', checkCustom );

	if ( newPHPCronElement ) {
		newCronElement.addEventListener( 'click', () => {
			editEventElement.classList.remove( 'crontrol-edit-event-php' );
			editEventElement.classList.add( 'crontrol-edit-event-standard' );
			hookNameElement.setAttribute( 'required', true );
		} );
		newPHPCronElement.addEventListener( 'click', () => {
			editEventElement.classList.remove( 'crontrol-edit-event-standard' );
			editEventElement.classList.add( 'crontrol-edit-event-php' );
			hookNameElement.removeAttribute( 'required' );
			if ( ! hookCodeElement.classList.contains( 'crontrol-editor-initialized' ) ) {
				wp.codeEditor.initialize( 'crontrol_hookcode', window.wpCrontrol.codeEditor );
			}
			hookCodeElement.classList.add( 'crontrol-editor-initialized' );
		} );
	} else if ( hookCodeElement ) {
		wp.codeEditor.initialize( 'crontrol_hookcode', window.wpCrontrol.codeEditor );
	}
} );
