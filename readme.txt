=== Admin Tweaks ===
Contributors: brasofilo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=JNJXKWBYM9JP6&lc=ES&item_name=Admin%20Tweaks%20%3a%20Rodolfo%20Buaiz&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: admin, admin interface, tuning, profile, posts, pages, login, maintenance mode, snippets, clients
Requires at least: 3.5
Tested up to: 4.8
Stable tag: 2.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

With Admin Tweaks you'll be able to simplify and make **deep customizations** in the administrative interface.
It's a compilation of hooks for enhancing, styling and reducing WordPress backend.

Do you like to adjust and style the backend as much as the frontend?
So, we are together!

= Main Features =
* Appearance: hide general elements; admin notices.
* Admin Bar: remove, add and modify menu items.
* Admin Menus: remove items; sort Settings menu; rename "Posts".
* Dashboard: remove and add widgets.
* Post and Page Listing: customize rows and columns.
* Post and Page Editing: auto-save; revisions; meta boxes.
* Media: custom columns; re-attachment; sanitize filenames; jpeg quality; audio/photo/video metadata.
* Widgets: remove default widgets; RSS timer.
* Plugins: many row modifications.
* Users and Profile: remove almost everything; add custom CSS.
* Shortcodes: enable shortcodes everywhere; GoogleDocs preview; YouTube like G+.
* General Settings: privacy; other misc options.
* Login: redirects; errors; modify almost everything; add custom CSS.
* Multisite: see the [FAQ](http://wordpress.org/plugins/many-tips-together/faq/).
* Maintenance Mode: with minimum Role allowed and possibility to block only the backend.

= Localizations =
* Português
* Español


== Installation ==
1. Upload `many-tips-together.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Go to *Settings -> Admin Tweaks* and have fun.

= Uninstall =
The 'reset' button doesn't delete the database entry, but if you delete the plugin, the entry will be deleted (via unsinstall.php)

== Frequently Asked Questions ==
= Why Many Tips Together? And why change its name to Admin Tweaks? =
The first version of the plugin was a compilation of snippets.
It evolved to a General Admin Tweak plugin.
Most of the users who left feedback complained about it: too cryptic and hard to find.
Well, I agree, but I'm just changing the Display Name.
The Repository URL, Directory Name and Database Option Name are still keep original name.

= Multisite =
The MS features appear in the main site Admin Tweaks screen, there's no Network screen for it.
They are only visible to super admins and in the main site settings page.
Although fully functional, all this would be better as a separate plugin that can be network activated.
Consider the MS features available right now as an experiment subject to removal in favor of a new plugin.

= Login CSS =
Try disabling the default styles and paste [this example](https://gist.github.com/brasofilo/6770339). It's a bit of a mess of styles, but can give you some ideas.

= Doubts, bugs, suggestions =
Don't hesitate in posting a new topic here in [WordPress support forum](https://wordpress.org/support/plugin/many-tips-together).


== Screenshots ==
1. Plugin settings, Profile page adjustments
2. Profile page with adjustments
3. Website with Maintenance Mode enabled
4. Customized Login page
5. Post Listing with ID and Thumbnail columns. Draft posts with another background color. Help tab hidden.
6. Media Library with ID column, bigger Thumbnails and All Thumbs listing. Re-attachment enabled. Download button in action rows.
7. Plugins page with different color for Inactive and custom color for selected authors. Simpler description and Last Updated information.
8. Multisite support.

== Changelog ==

**Version 2.4.1**

* Review for WordPress 4.8

**Version 2.4**

* General revision to update to WP 4.7
* Removed obsolete tweaks
* Bug fixes

**Older versions**

* [Browse archives](https://plugins.trac.wordpress.org/browser/many-tips-together/tags/2.4/readme.txt).

== Upgrade Notice ==

= 2.4.1 =
- Reviewed up to WordPress 4.8

== Acknowledgments ==

* Everything changed after [WordPress Stack Exchange](http://wordpress.stackexchange.com/)
* Plugin interface using @bainternet's [Admin Page Class](https://github.com/bainternet/Admin-Page-Class)
* CSS for hiding help texts adapted from [Admin Expert Mode](http://wordpress.org/extend/plugins/admin-expert-mode/)
* Everything started with [Adminimize](http://wordpress.org/extend/plugins/adminimize/), by Frank Büeltge, which does an awesome job hiding WordPress elements, but I wanted more, and these are some of the great resources where I found many snippets: [Stack Exchange](http://wordpress.stackexchange.com/questions/1567/best-collection-of-code-for-your-functions-php-file), [WPengineer](http://wpengineer.com), [wpbeginner](http://www.wpbeginner.com), [CSS-TRICKS](http://css-tricks.com), [Smashing Magazine](http://wp.smashingmagazine.com), [Justin Tadlock](http://justintadlock.com)...
* The option to hide the help texts from many areas of WordPress uses the CSS file of the plugin [Admin Expert Mode](http://wordpress.org/extend/plugins/admin-expert-mode/), by Scott Reilly.
