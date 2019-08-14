=== Server IP & Memory Usage Display ===
Contributors: apasionados
Donate link: http://apasionados.es/
Author URI: http://apasionados.es/
Tags: memory, memory-limit, ip, ips, admin, adress, php, server, info
Requires at least: 3.0.1
Tested up to: 4.9
Requires PHP: 5.3
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show the memory limit, current memory usage and IP address in the admin footer.

== Description ==

This plugin displays the memory limit, current memory usage, WordPress Memory Limit, IP address and PHP version in the admin footer.

There are four features we love:

1) You can easily see in the admin footer the IP where this WordPress installation is running. Very useful if you have many WordPress installations across several servers and IP addresses.

2) The memory usage and total available memory to the WordPress installation is shown in the admin footer. If memory usage is over 75% the percentage is shown in a light red and if the memory usage is over 90% the percentage is shown in red. So you can allways control with one look if there is enough memory available or if action has to be taken. It also displays the WordPress memory limit to give you more information.

3) Besides the IP address and the memory you can also see the PHP version and the type of Operating System where it's running.

4) You can translate the plugin into your own language. So far English and Spanish translations are included. Just translate the .po file in the /lang/ folder.

= What can I do with this plugin? =

This plugin displays the total memory, used memory, percentage of used memory, WP memory limit, the IP address and the PHP version in the admin footer.

= What ideas is this plugin based on? =

We had been using the WordPress plugin [WP-Memory-Usage](http://wordpress.org/plugins/wp-memory-usage/ "WP-Memory-Usage") but didn't want the dashboard widget and needed the IP address displayed. We also didn't like that the plugin could not be translated.

This is why we decided to create a new plugin that solves these two explained needs: IP address display and ability to translate.

= System requirements =

PHP version 5.3 or greater.

= Server IP & Memory Usage Display Plugin in your Language! =
This first release is avaliable in English and Spanish. In the "lang" folder we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-ip-address-memory-usage-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Server IP & Memory Usage Display en espa&ntilde;ol](http://apasionados.es/blog/server-ip-memory-usage-display-wordpress-plugin-1781/).

== Screenshots ==

1. Example of the footer displaying the information.
2. Example of footer when memory usage is over 75%.
3. Example of footer when memory usage is over 90%.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
3. Now the information regarding memory limit, current memory usage, IP address and PHP version is displayed in the admin footer.

Please note that the plugin should not be used together with other plugins with similar funcionalities like: [WP-Memory-Usage](http://wordpress.org/plugins/wp-memory-usage/ "WP-Memory-Usage").

Please use with *WordPress MultiSite* at your own risk, as it has not been tested.

== Frequently Asked Questions ==

= Why did you make this plugin?  =

We couldn't find a plugin that would give us the functionality we were looking for:
1) IP address display in footer of WordPress admin.
2) Total memory and memory usage displayed in the admin footer, without dashboard plugins. 
3) Easy translation of the plugin into other languages. So far English and Spanish translations are included.

= How can I remove Server IP & Memory Usage Display? =
You can simply activate, deactivate or delete it in your plugin management section.

= Are there any known incompatibilities? =
The plugin should not be used together with other plugins with similar funcionalities like: [WP-Memory-Usage](http://wordpress.org/plugins/wp-memory-usage/ "WP-Memory-Usage").
Please use with *WordPress MultiSite* at your own risk, as it has not been tested.

= Are there any server requirements? =
The PHP version should be at least 5.3. If PHP version is lower than 5.3 there is an error message shown and plugin is not activated.

= Do you make use of Server IP & Memory Usage Display yourself? = 
Of course we do. That's why we created it. ;-)

== Changelog ==

= 2.1.0 (03/JAN/2018) =
* Added compatibility with PHP 7.2.x: "function create_function() is deprecated in PHP 7.2". Thanks to @pputzer.

= 2.0.3 =
* Solved error with memory_get_peak_usage().

= 2.0.2 =
* Solved: PHP 7.1 Notice: A non well formed numeric value encountered for $result

= 2.0.1 =
* Changed memory information display to use memory_get_peak_usage().

= 2.0.0 =
* Added additional information about memory_get_peak_usage() - support ticket by @diablodale

= 1.4.0 =
* Added check for PHP version of the server. Plugins works with PHP 5.3 or greater. If PHP version is less than 5.3 the plugin is not activated.

= 1.3.3 =
* Added support for IP address with IIS using $_SERVER['LOCAL_ADDR'].

= 1.3.0 =
* Added Added hostname info after the IP.

= 1.2.2 =
* Added check to avoid error "Notice: Undefined index: SERVER_ADDR in" when SERVER_ADDR is not set. 

= 1.2.1 =
* Added display of WordPress Memory Limit information (parameter WP_MEMORY_LIMIT of wp-config.php). You can set this parameter in wp-config.php by adding this line: *define( 'WP_MEMORY_LIMIT', '768M' );* and replacing 768M with the memory limit you want to set.

= 1.1.0 =
* Changes for compatibility with PHP 7. Changed "function ip_address_memory_usage() {" to "public function __construct() {". Thanks to Sybre Waaijer for the fix.

= 1.0.3 =
* Removed call to __construct() which caused problems on some systems: ERROR "Strict Standards: Redefining already defined constructor for class ip_address_memory_usage in /home/---/public_html/wp-content/plugins/server-ip-memory-usage/server-ip-memory-usage.php on line 40)".

= 1.0.2 =
* Included info about Server Operating System
* Changed class name to c_ip_address_memory_usage to avoid error on some systems: "Redefining already defined constructor for class ip_address_memory_usage".

= 1.0.1 =
* First official release.

== Upgrade Notice ==

= 2.1.0 =
* Added compatibility with PHP 7.2.x: "function create_function() is deprecated in PHP 7.2".

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-ipaddressmemoryusage-contact).