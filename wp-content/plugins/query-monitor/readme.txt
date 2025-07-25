# Query Monitor - The developer tools panel for WordPress

Contributors: johnbillion
Tags: debug, debug-bar, development, performance, query monitor
Tested up to: 6.8
Stable tag: 3.19.0
License: GPL v2 or later
Donate link: https://github.com/sponsors/johnbillion

Query Monitor is the developer tools panel for WordPress and WooCommerce.

## Description

Query Monitor is the developer tools panel for WordPress and WooCommerce. It enables debugging of database queries, PHP errors, hooks and actions, block editor blocks, enqueued scripts and stylesheets, HTTP API calls, and more.

It includes some advanced features such as debugging of Ajax calls, REST API calls, user capability checks, and full support for block themes and full site editing. It includes the ability to narrow down much of its output by plugin or theme, allowing you to quickly determine poorly performing plugins, themes, or functions.

Query Monitor focuses heavily on presenting its information in a useful manner, for example by showing aggregate database queries grouped by the plugins, themes, or functions that are responsible for them. It adds an admin toolbar menu showing an overview of the current page, with complete debugging information shown in panels once you select a menu item.

Query Monitor supports versions of WordPress up to three years old, and PHP version 7.4 or higher.

For complete information, please see [the Query Monitor website](https://querymonitor.com/).

Here's an overview of what's shown for each page load:

* Database queries, including notifications for slow, duplicate, or erroneous queries. Allows filtering by query type (`SELECT`, `UPDATE`, `DELETE`, etc), responsible component (plugin, theme, WordPress core), and calling function, and provides separate aggregate views for each.
* The template filename, the complete template hierarchy, and names of all template parts that were loaded or not loaded (for block themes and classic themes).
* PHP errors presented nicely along with their responsible component and call stack, and a visible warning in the admin toolbar.
* Usage of "Doing it Wrong" or "Deprecated" functionality in the code on your site.
* Blocks and associated properties within post content and within full site editing (FSE).
* Matched rewrite rules, associated query strings, and query vars.
* Enqueued scripts and stylesheets, along with their dependencies, dependents, and alerts for broken dependencies.
* Language settings and loaded translation files (MO files and JSON files) for each text domain.
* HTTP API requests, with response code, responsible component, and time taken, with alerts for failed or erroneous requests.
* User capability checks, along with the result and any parameters passed to the capability check.
* Environment information, including detailed information about PHP, the database, WordPress, and the web server.
* The values of all WordPress conditional functions such as `is_single()`, `is_home()`, etc.
* Transients that were updated.
* Usage of `switch_to_blog()` and `restore_current_blog()` on Multisite installations.

In addition:

* Whenever a redirect occurs, Query Monitor adds an HTTP header containing the call stack, so you can use your favourite HTTP inspector or browser developer tools to trace what triggered the redirect.
* The response from any jQuery-initiated Ajax request on the page will contain various debugging information in its headers. PHP errors also get output to the browser's developer console.
* The response from an authenticated WordPress REST API request will contain an overview of performance information and PHP errors in its headers, as long as the authenticated user has permission to view Query Monitor's output. An [an enveloped REST API request](https://developer.wordpress.org/rest-api/using-the-rest-api/global-parameters/#_envelope) will include even more debugging information in the `qm` property of the response.

By default, Query Monitor's output is only shown to Administrators on single-site installations, and Super Admins on Multisite installations.

In addition to this, you can set an authentication cookie which allows you to view Query Monitor output when you're not logged in (or if you're logged in as a non-Administrator). See the Settings panel for details.

### Other Plugins

I maintain several other plugins for developers. Check them out:

* [User Switching](https://wordpress.org/plugins/user-switching/) provides instant switching between user accounts in WordPress.
* [WP Crontrol](https://wordpress.org/plugins/wp-crontrol/) lets you view and control what's happening in the WP-Cron system

### Privacy Statement

Query Monitor is private by default and always will be. It does not persistently store any of the data that it collects. It does not send data to any third party, nor does it include any third party resources. [Query Monitor's full privacy statement can be found here](https://querymonitor.com/privacy/).

### Accessibility Statement

Query Monitor aims to be fully accessible to all of its users. [Query Monitor's full accessibility statement can be found here](https://querymonitor.com/accessibility/).

## Screenshots

1. Admin Toolbar Menu
2. Aggregate Database Queries by Component
3. Capability Checks
4. Database Queries
5. Hooks and Actions
6. HTTP API Requests
7. Aggregate Database Queries by Calling Function

## Frequently Asked Questions

### Does this plugin work with PHP 8?

Yes, it's actively tested and working up to PHP 8.4.

### Who can see Query Monitor's output?

By default, Query Monitor's output is only shown to Administrators on single-site installations, and Super Admins on Multisite installations.

In addition to this, you can set an authentication cookie which allows you to view Query Monitor output when you're not logged in, or when you're logged in as a user who cannot usually see Query Monitor's output. See the Settings panel for details.

### Does Query Monitor itself impact the page generation time or memory usage?

Short answer: Yes, but only a little.

Long answer: Query Monitor has a small impact on page generation time because it hooks into a few places in WordPress in the same way that other plugins do. The impact is negligible.

On pages that have an especially high number of database queries (in the hundreds), Query Monitor currently uses more memory than I would like it to. This is due to the amount of data that is captured in the stack trace for each query. I have been and will be working to continually reduce this.

### Can I prevent Query Monitor from collecting data during long-running requests?

Yes, you can call `do_action( 'qm/cease' )` to instruct Query Monitor to cease operating for the remainder of the page generation. It will detach itself from further data collection, discard any data it's collected so far, and skip the output of its information.

This is useful for long-running operations that perform a very high number of database queries, consume a lot of memory, or otherwise are of no concern to Query Monitor, for example:

* Backing up or restoring your site
* Importing or exporting a large amount of data
* Running security scans

### Are there any add-on plugins for Query Monitor?

[A list of add-on plugins for Query Monitor can be found here.](https://querymonitor.com/help/add-on-plugins/)

In addition, Query Monitor transparently supports add-ons for the Debug Bar plugin. If you have any Debug Bar add-ons installed, deactivate Debug Bar and the add-ons will show up in Query Monitor's menu.

### Where can I suggest a new feature or report a bug?

Please use [the issue tracker on Query Monitor's GitHub repo](https://github.com/johnbillion/query-monitor/issues) as it's easier to keep track of issues there, rather than on the wordpress.org support forums.

### Is Query Monitor already included with my hosting?

Some web hosts bundle Query Monitor as part of their hosting platform, which means you don't need to install it yourself. Here are some that I'm aware of:

* [Altis Cloud](https://www.altis-dxp.com/resources/developer-docs/dev-tools/).
* [WordPress VIP](https://wpvip.com/), although users need to be granted the `view_query_monitor` capability even if they're an Administrator. [See the WordPress VIP documentation for details](https://docs.wpvip.com/performance/query-monitor/enable/).

### Can I click on stack traces to open the file in my editor?

Yes. You can enable this on the Settings panel.

### How can I report a security bug?

[You can report security bugs through the official Query Monitor Vulnerability Disclosure Program on Patchstack](https://patchstack.com/database/vdp/query-monitor). The Patchstack team helps validate, triage, and handle any security vulnerabilities.

### Do you accept donations?

[I am accepting sponsorships via the GitHub Sponsors program](https://github.com/sponsors/johnbillion). If you work at an agency that develops with WordPress, ask your company to provide sponsorship in order to invest in its supply chain. The tools that I maintain probably save your company time and money, and GitHub sponsorship can now be done at the organisation level.

In addition, if you like the plugin then I'd love for you to [leave a review](https://wordpress.org/support/view/plugin-reviews/query-monitor). Tell all your friends about it too!
## Changelog ##

### 3.19.0 (23 July 2025) ###

* Adds Guzzle middleware support for logging HTTP client requests.
* Fixes plugin conflicts caused by the global `qm` JavaScript variable by renaming it to `QueryMonitorData`.
* Corrects invalid HTML markup where `<th>` elements were closed with `</td>` tags.

### 3.18.0 (16 June 2025) ###

* Adds more comprehensive handling of HTTP API requests which were overridden by the `pre_http_request` filter.
* Corrects the handling of suppressed PHP errors on both PHP 7 and PHP 8.
* Confirms support for WordPress 6.8.

### 3.17.2 (4 February 2025) ###

* Reinstates the "Blocks" panel

### 3.17.1 (2 February 2025) ###

* Prevents use of the deprecated `E_STRICT` constant in PHP 8.4.
* Avoids use of the deprecated `setted_transient` and `setted_site_transient` actions in WordPress 6.8.
* Skips showing the `_load_textdomain_just_in_time` notices when they're caused by Query Monitor itself.
* Uses more appropriate formatting for a fatal error in REST API and Ajax contexts.


### 3.17.0 (27 November 2024) ###

* Support for WordPress 6.7.
* Support for PHP 8.4.
* Inline scripts are now output using `wp_print_inline_script_tag()` so a Content Security Policy can be fully implemented.
* Various improvements and fixes.

### 3.16.4 (25 July 2024) ###

* Confirms support for WordPress 6.6.

### 3.16.3 (22 May 2024) ###

* Prevents an infinite loop when logging doing it wrong calls and deprecated calls.
* Removes a global from query-monitor.php

### 3.16.2 (22 May 2024) ###

* Fixes another issue with the PHP autoloader in 3.16.0 and 3.16.1 that was crashing some sites

### 3.16.1 (22 May 2024) ###

* Fixes an issue with the PHP autoloader in 3.16.0 that was crashing some sites

### 3.16.0 (22 April 2024) ###

* Adds full support for debugging new features in WordPress 6.5: JavaScript modules and PHP translation files

### Earlier versions ###

For the changelog of earlier versions, <a href="https://github.com/johnbillion/query-monitor/releases">refer to the releases page on GitHub</a>.
