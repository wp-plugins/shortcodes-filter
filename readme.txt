=== Shortcodes Filter ===
Contributors: wiziapp
Donate link: https://wordpress.org/plugins/wiziapp-create-your-own-native-iphone-app/
Tags: shortcodes, filter, wiziapp, mobile, mobile theme, theme switcher
Requires at least: 3.0.1
Tested up to: 4.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Filter certain shortcodes out of posts and pages on mobile or desktop display

== Description ==

Wordpress themes sometimes adds hardcoded shortcodes into your posts and pages and these shortcodes may display even on user agents which are using other themes.

For example, a Wordpress site is using a certain theme for desktop view and “Wiziapp” powered theme for mobile view and unwanted desktop theme shortcodes displays on mobile, just use this plugin to clear them from mobile devices.

An example: To clear a shortcode [Xxx], add to the “Shortcodes Filter” plugin the filter “[Xxx]” for a specific filter or “[X” for a broader one.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 1.0.1 =

- Fixed bug in settings page
- Cleaned up global scope


== Upgrade Notice ==


== Arbitrary section ==



== A brief Markdown Example ==

