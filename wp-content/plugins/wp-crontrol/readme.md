# WP Crontrol

Contributors: johnbillion, scompt  
Tags: cron, wp-cron, crontrol, debug, woocommerce  
Tested up to: 6.6  
Stable tag: 1.17.0  
Donate link: https://github.com/sponsors/johnbillion

WP Crontrol enables you to take control of the cron events on your WordPress website.

## Description

WP Crontrol enables you to take control of the cron events on your WordPress website. From the admin screens you can:

 * View all cron events along with their arguments, schedule, callback functions, and when they are next due.
 * Edit, delete, pause, resume, and immediately run cron events.
 * Add new cron events.
 * Bulk delete cron events.
 * Add and remove custom cron schedules.
 * Export and download cron event lists as a CSV file.

WP Crontrol is aware of timezones, will alert you to events that have no actions or that have missed their schedule, and will show you a helpful warning message if it detects any problems with your cron system.

### Usage

1. Go to the `Tools → Cron Events` menu to manage cron events.
2. Go to the `Settings → Cron Schedules` menu to manage cron schedules.

### Other Plugins

I maintain several other plugins for developers. Check them out:

* [Query Monitor](https://wordpress.org/plugins/query-monitor/) is the developer tools panel for WordPress.
* [User Switching](https://wordpress.org/plugins/user-switching/) provides instant switching between user accounts in WordPress.

### Privacy Statement

WP Crontrol is private by default and always will be. It does not send data to any third party, nor does it include any third party resources. [WP Crontrol's full privacy statement can be found here](https://wp-crontrol.com/privacy/).

### Accessibility Statement

WP Crontrol aims to be fully accessible to all of its users. [WP Crontrol's full accessibility statement can be found here](https://wp-crontrol.com/accessibility/).

## Frequently Asked Questions

### Does this plugin work with PHP 8?

Yes, it's actively tested and working up to PHP 8.3.

### I get the error "There was a problem spawning a call to the WP-Cron system on your site". How do I fix this?

[You can read all about problems spawning WP-Cron on the WP Crontrol website](https://wp-crontrol.com/help/problems-spawning-wp-cron/).

### Why do some cron events miss their schedule?

[You can read all about cron events that miss their schedule on the WP Crontrol website](https://wp-crontrol.com/help/missed-cron-events/).

### Why do some cron events reappear shortly after I delete them?

If the event is added by a plugin then the plugin most likely rescheduled the event as soon as it saw that the event was missing. To get around this you can instead use the "Pause this hook" action which means it'll remain in place but won't perform any action when it runs.

### Is it safe to delete cron events?

This depends entirely on the event. You can use your favourite search engine to search for the event name in order to find out which plugin it belongs to, and then decide whether or not to delete it.

If the event shows "None" as its action then it's usually safe to delete. Please see the other FAQs for more information about events with no action.

### Why can't I delete some cron events?

The WordPress core software uses cron events for some of its functionality and removing these events is not possible because WordPress would immediately reschedule them if you did delete them. For this reason, WP Crontrol doesn't let you delete these persistent events from WordPress core in the first place.

If you don't want these events to run, you can use the "Pause this hook" action instead.

### What happens when I pause an event?

Pausing an event will disable all actions attached to the event's hook. The event itself will remain in place and will run according to its schedule, but all actions attached to its hook will be disabled. This renders the event inoperative but keeps it scheduled so as to remain fully compatible with events which would otherwise get automatically rescheduled when they're missing.

As pausing an event actually pauses its hook, all events that use the same hook will be paused or resumed when pausing and resuming an event. This is much more useful and reliable than pausing individual events separately.

### What happens when I resume an event?

Resuming an event re-enables all actions attached to the event's hook. All events that use the same hook will be resumed.

### What does it mean when "None" is shown for the Action of a cron event?

This means the cron event is scheduled to run at the specified time but there is no corresponding functionality that will be triggered when the event runs, therefore the event is useless.

This is often caused by plugins that don't clean up their cron events when you deactivate them. You can use your favourite search engine to search for the event name in order to find out which plugin it belongs to, and then decide whether or not to delete it.

### How do I change the next run time or the schedule of a cron event?

You can change the time and schedule of a cron event by clicking the "Edit" link next to the event.

### How can I create a cron event that requests a URL?

From the Tools → Cron Events menu, click on Add New Cron Event. Select the "URL cron event" option, fill out the details, and press the "Add Event" button.

[You can read all about the features and security of URL cron events on the WP Crontrol website](https://wp-crontrol.com/docs/url-cron-events/).

### Why do changes that I make to some cron events not get saved?

[You can read all about problems with editing cron events on the WP Crontrol website](https://wp-crontrol.com/help/problems-managing-events/).

### Can I export a list of cron events?

Yes, a CSV file of the event list can be exported and downloaded via the "Export" button on the cron event listing screen. This file can be opened in any spreadsheet application.

### Can I see a historical log of all the cron events that ran on my site?

Not yet, but I hope to add this functionality soon.

### Can I see a historical log of edits, additions, and deletions of cron events and schedules?

Yes. The excellent <a href="https://wordpress.org/plugins/simple-history/">Simple History plugin</a> has built-in support for logging actions performed via WP Crontrol.

### What's the use of adding new cron schedules?

Cron schedules are used by WordPress and plugins for scheduling events to be executed at regular intervals. Intervals must be provided by the WordPress core or a plugin in order to be used. As an example, many backup plugins provide support for periodic backups. In order to do a weekly backup, a weekly cron schedule must be entered into WP Crontrol first and then a backup plugin can take advantage of it as an interval.

### How do I create a new cron event?

There are two steps to getting a functioning cron event that executes regularly. The first step is telling WordPress about the hook. This is the part that WP Crontrol was created to provide. The second step is calling a function when your hook is executed.

*Step One: Adding the event*

From the Tools → Cron Events menu, click on Add New Cron Event. Fill out the details of the event. You're best off using a hook name that conforms to normal PHP variable naming conventions. The schedule is how often the event will be executed. If you don't see a good interval, then add one in the Settings → Cron Schedules menu.

*Step Two: Writing the function*

This part takes place in PHP code (for example, in the `functions.php` file from your theme). To execute your hook, WordPress runs an action. For this reason, we need to tell WordPress which function to execute when this action is run. The following line accomplishes that:

~~~php
add_action( 'my_hookname', 'my_function' );
~~~

The next step is to write your function. Here's a simple example:

~~~php
function my_function() {
	wp_mail( 'hello@example.com', 'WP Crontrol', 'WP Crontrol rocks!' );
}
~~~

### How do I create a new PHP cron event?

From the Tools → Cron Events menu, click on "Add New Cron Event". Select the "PHP cron event" option and enter the schedule and next run time. The event schedule is how often your event will be executed. If you don't see a good interval, then add one in the Settings → Cron Schedules menu. In the "PHP Code" area, enter the PHP code that should be run when your cron event is executed. You don't need to provide the PHP opening tag (`<?php`).

Creating, editing, and running PHP cron events is subject to restrictive security permissions. [You can read all about the features and security of PHP cron events on the WP Crontrol website](https://wp-crontrol.com/docs/php-cron-events/).

### Which users can manage cron events and schedules?

Only users with the `manage_options` capability can manage cron events and schedules. By default, only Administrators have this capability.

### Which users can manage PHP cron events? Is this dangerous?

Only users with the `edit_files` capability can manage PHP cron events. This means if a user cannot edit files via the WordPress admin area (i.e. through the Plugin Editor or Theme Editor) then they also cannot add, edit, or delete a PHP cron event in WP Crontrol. By default only Administrators have this capability, and with Multisite enabled only Super Admins have this capability.

If file editing has been disabled via the `DISALLOW_FILE_MODS` or `DISALLOW_FILE_EDIT` configuration constants then no user will have the `edit_files` capability, which means adding, editing, or deleting a PHP cron event will not be permitted.

Therefore, the user access level required to execute arbitrary PHP code does not change with WP Crontrol activated.

[You can read all about the features and security of PHP cron events on the WP Crontrol website](https://wp-crontrol.com/docs/php-cron-events/).

### Are any WP-CLI commands available?

The cron commands which were previously included in WP Crontrol are now part of WP-CLI itself. See `wp help cron` for more info.

### What happens when I deactivate the WP Crontrol plugin?

[You can read all about what happens when you deactivate the plugin on the WP Crontrol website](https://wp-crontrol.com/docs/deactivation/).

### How can I report a security bug?

[You can report security bugs through the official WP Crontrol Vulnerability Disclosure Program on Patchstack](https://patchstack.com/database/vdp/wp-crontrol). The Patchstack team helps validate, triage, and handle any security vulnerabilities.

### Who took the photo in the plugin header image?

The photo was taken by <a href="https://www.flickr.com/photos/michaelpardo/21453119315">Michael Pardo</a> and is in the public domain.

## Screenshots

1. Cron events can be modified, deleted, and executed<br>![](.wordpress-org/screenshot-1.png)

2. New cron events can be added<br>![](.wordpress-org/screenshot-2.png)

3. New cron schedules can be added, giving plugin developers more options when scheduling events<br>![](.wordpress-org/screenshot-3.png)

<!-- changelog -->

## Changelog ##

### 1.17.0 (15 July 2024) ###

* Introduces [a new cron event type for sending a request to a URL](https://wp-crontrol.com/docs/url-cron-events/)
* Confirms support for WordPress 6.6
* Improves various aspects of the cron management interface and language


### 1.16.3 (19 April 2024) ###

* Corrects the displayed PHP cron event name if one is provided

### 1.16.2 (24 March 2024) ###

* Security hardening: [An anti-tampering mechanism has been introduced for PHP cron events](https://wp-crontrol.com/help/check-php-cron-events/)
* Improvements to accessibility and internationalisation
* Removes the dependency on jQuery
* Confirms support for WordPress 6.5


### 1.16.1 (16 November 2023) ###

* Confirms support for WordPress 6.4

### 1.16.0 (17 October 2023) ###

* Allow persistent WordPress core hooks to be cleared if there's more than one event with that hook
* Add the number of matching events to the hook deletion link text
* Scrap the Ajax request that checks if the current page of cron events has changed since loading
* Make some improvements to sorting the cron event list table columns
* Increase the minimum supported PHP version to 7.4


### 1.15.3 (30 June 2023) ###

* Pass the `$doing_wp_cron` value to the `cron_request` filter so it matches WordPress core
* Miscellaneous code quality improvements

### 1.15.2 (15 March 2023) ###

* Improves the terminology around pausing and deleting hooks and events
* Improves accessibility of the event listing table for keyboard users
* Removes an unnecessary SQL query when fetching the list of paused events
* Adds an FAQ about deactivating the plugin

### 1.15.1 (29 December 2022) ###

* Confirms the plugin is compatible with PHP 8.2
* Increases compatibility with other plugins that include very old Composer autoloader implementations

### 1.15.0 (29 November 2022) ###

* Introduces the ability to pause and resume cron events from the event listing screen; see the FAQ for full details
* Adds the site time to the cron event editing screen
* Implements an autoloader to reduce memory usage
* Bumps the minimum supported version of PHP to 5.6

### 1.14.0 (10 July 2022) ###

* Reverts the changes introduced in version 1.13 while I look into the problem with the deployment process for wordpress.org

### 1.13.2 (10 July 2022) ###

* Fixes another issue with missing files in the release

### 1.13.1 (10 July 2022) ###

* Fixes an issue with missing files in the 1.13.0 release

### 1.13.0 (10 July 2022) ###

* Introduces the ability to pause and resume cron events from the event listing screen; see the FAQ for full details
* Implements an autoloader to reduce memory usage
* Bumps the minimum supported version of PHP to 5.6

### 1.12.1 (15 April 2022) ###

* Corrects an issue where an invalid hook callback isn't always identified
* Various code quality improvements

### 1.12.0 (22 November 2021) ###

* Fix the PHP cron event management.
* More "namespacing" of query variables to avoid conflicts with other cron management plugins.

### Earlier versions ###

For the changelog of earlier versions, <a href="https://github.com/johnbillion/wp-crontrol/releases">please refer to the releases page on GitHub</a>.