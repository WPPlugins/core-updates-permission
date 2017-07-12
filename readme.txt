=== Core Updates Permission ===
Contributors: mauteri
Tags: disable, updates, theme, core
Requires at least: 2.8
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disables the theme, plugin and core update checking, the related cronjobs and notification system by default. Provides the ability to give certain administrators the ability to see and make updates.

== Description ==

This plugin is a fork of `Disable All WordPress Updates` plugin. Like that plugin, Core Updates Permission completely disables the theme, plugin and core update checking system in WordPress. The plugin prevents WordPress from checking for updates including cronjobs, and prevents any notifications from being displayed. The one significant difference is that this plugin also allows you to pick and choose administrators that *can* have the ability to make updates.

It's *very* important that you keep your WordPress theme, core and plugins up to date. If you don't, your blog or website could be **susceptible to security vulnerabilities** or performance issues.

If you use this plugin, it's a good idea to give one administrator the ability to see update notifications to keep up to date with new releases of your active WordPress version, plugins and themes and update them as new versions are released. 

== Installation ==

1. Download the plugin and unzip it.
2. Upload the folder disable-wordpress-updates/ to your /wp-content/plugins/ folder.
3. Activate the plugin from your WordPress admin panel.
4. Edit Your Profile or that of an administrator that you want to see updates. Look for `Core Updates Permission` and choose `Yes` to give the ability to see core updates. 


With activating the plugin all theme, core and plugin update checkings are disabled. You can then provide certain administrators with the ability to see the update notifications by editing their profile. Usually, you want to only give developer administrator accounts this ability.

== Screenshots ==

None. :)


== Changelog ==

= 1.4.0.1 =
* Added functionality to hide update notifications.

= 1.4 =
* Forked from `Disable All WordPress Updates` plugin.
* Added user setting for making Core Updates.

= 1.3.0.1 =
* Minor updates

= 1.3 =
* New plugin maintainer. Hello. :) Name's Oliver.
* Code rewrite and cleanup

= 1.2 =
* Name and URL Update (German plugin description updated)

= 1.1 =
* URL Update because of permalink changes

= 1.0 =
* Initial release

