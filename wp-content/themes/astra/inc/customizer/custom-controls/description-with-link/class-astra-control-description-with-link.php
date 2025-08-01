<?php
/**
 * Customizer Control: description-with-link
 *
 * @package     Astra
 * @link        https://wpastra.com/
 * @since       4.11.8
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A description control with embedded customizer link.
 */
class Astra_Control_Description_With_Link extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $type = 'ast-description-with-link';

	/**
	 * The control type.
	 *
	 * @var string
	 */
	public $help = '';

	/**
	 * Link text to be added inside the anchor tag.
	 *
	 * @var string
	 */
	public $link_text = '';

	/**
	 * Linked customizer section.
	 *
	 * @var string
	 */
	public $linked = '';

	/**
	 * Linked customizer section.
	 *
	 * @var string
	 */
	public $link_type = '';

	/**
	 * True if the link is button.
	 *
	 * @var bool
	 */
	public $is_button_link = '';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['label']          = esc_html( $this->label );
		$this->json['description']    = $this->description;
		$this->json['help']           = $this->help;
		$this->json['link_text']      = $this->link_text;
		$this->json['linked']         = $this->linked;
		$this->json['link_type']      = $this->link_type;
		$this->json['is_button_link'] = $this->is_button_link;
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {
	}
}
