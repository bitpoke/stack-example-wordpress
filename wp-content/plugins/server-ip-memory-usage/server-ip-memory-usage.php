<?php
/*
Plugin Name: Server IP & Memory Usage Display
Plugin URI: http://apasionados.es/#utm_source=wpadmin&utm_medium=plugin&utm_campaign=server-ip-memory-usage-plugin
Description: Show the memory limit, current memory usage and IP address in the admin footer.
Version: 2.1.0
Author: Apasionados, Apasionados del Marketing
Author URI: http://apasionados.es
Text Domain: server-ip-memory-usage

# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	$plugin_header_translate = array( __('Server IP & Memory Usage Display', 'server-ip-memory-usage'), __('Show the memory limit, current memory usage and IP address in the admin footer.', 'server-ip-memory-usage') );

if ( is_admin() ) {	

	class ip_address_memory_usage {

		var $memory = false;
		var $server_ip_address = false;

		public function __construct() {
			add_action( 'admin_init', 'ipmem_load_language' );
			function ipmem_load_language() {
				load_plugin_textdomain( 'server-ip-memory-usage', false,  dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
			}	
            add_action( 'init', array (&$this, 'check_limit') );
			add_filter( 'admin_footer_text', array (&$this, 'add_footer') );
			$this->memory = array();					
		}
      
        function check_limit() {
            $this->memory['limit'] = (int) ini_get('memory_limit') ;
        }
		
		function check_memory_usage() {
			//$this->memory['usage'] = function_exists('memory_get_usage') ? round(memory_get_usage() / 1024 / 1024, 2) : 0;
			// Changed memory_get_usage to memory_get_peak_usage
			$this->memory['usage'] = function_exists('memory_get_peak_usage') ? round(memory_get_peak_usage(TRUE) / 1024 / 1024, 2) : 0;			
			
			if ( !empty($this->memory['usage']) && !empty($this->memory['limit']) ) {
				$this->memory['percent'] = round ($this->memory['usage'] / $this->memory['limit'] * 100, 0);
				$this->memory['color'] = 'font-weight:normal;';
				if ($this->memory['percent'] > 75) $this->memory['color'] = 'font-weight:bold;color:#E66F00';
				if ($this->memory['percent'] > 90) $this->memory['color'] = 'font-weight:bold;color:red';
			}		
		}

		function format_wp_limit( $size ) {
			$value  = substr( $size, -1 );
			$return = substr( $size, 0, -1 );

			$return = (int)$return; // Solved: PHP 7.1 Notice: A non well formed numeric value encountered 
	
			switch ( strtoupper( $value ) ) {
				case 'P' :
					$return*= 1024;
				case 'T' :
					$return*= 1024;
				case 'G' :
					$return*= 1024;
				case 'M' :
					$return*= 1024;
				case 'K' :
					$return*= 1024;
			}
			return $return;
		}  
		function check_wp_limit() {
			$memory = $this->format_wp_limit( WP_MEMORY_LIMIT );
			$memory = size_format( $memory );
			return ($memory) ? $memory : __( 'N/A', 'server-ip-memory-usage' );
		}

		function add_footer($content) {
			$this->check_memory_usage();
			//$server_ip_address = $_SERVER[ 'SERVER_ADDR' ];
			$server_ip_address = (!empty($_SERVER[ 'SERVER_ADDR' ]) ? $_SERVER[ 'SERVER_ADDR' ] : "");
			if ($server_ip_address == "") { // Added for IP Address in IIS
				$server_ip_address = (!empty($_SERVER[ 'LOCAL_ADDR' ]) ? $_SERVER[ 'LOCAL_ADDR' ] : "");
			}
			$content .= ' | ' . __( 'Memory', 'server-ip-memory-usage' ) . ': ' . $this->memory['usage'] . ' ' . __( 'of', 'server-ip-memory-usage' ) . ' ' . $this->memory['limit'] . ' MB (<span style="' . $this->memory['color'] . '">' . $this->memory['percent'] . '%</span>) | ' . __( 'WP LIMIT', 'server-ip-memory-usage' ) . ': ' . $this->check_wp_limit() . ' | IP ' . $server_ip_address . ' (' . gethostname() . ') | PHP ' . PHP_VERSION . ' @' . (PHP_INT_SIZE * 8) . 'BitOS';
			return $content;
		}

	}

	add_action( 'plugins_loaded', function() { 
		$memory = new ip_address_memory_usage();
	} );
}

/**
 * Do some check on plugin activation
 * @return void
 */
function server_ip_memory_usage_activation() {
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		load_plugin_textdomain( 'server-ip-memory-usage', false,  dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];
		$plugin_name = $plugin_data['Name'];
		wp_die( '<h1>' . __('Could not activate plugin: PHP version error', 'server-ip-memory-usage' ) . '</h1><h2>PLUGIN: <i>' . $plugin_name . ' ' . $plugin_version . '</i></h2><p><strong>' . __('You are using PHP version', 'server-ip-memory-usage' ) . ' ' . PHP_VERSION . '</strong>. ' . __( 'This plugin has been tested with PHP versions 5.3 and greater.', 'server-ip-memory-usage' ) . '</p><p>' . __('WordPress itself <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using PHP version 7 or greater</a>. Please upgrade your PHP version or contact your Server administrator.', 'server-ip-memory-usage' ) . '</p>', __('Could not activate plugin: PHP version error', 'server-ip-memory-usage' ), array( 'back_link' => true ) );

	}
}
register_activation_hook( __FILE__, 'server_ip_memory_usage_activation' );