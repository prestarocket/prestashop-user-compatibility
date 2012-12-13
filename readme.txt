=== Prestashop Users Compat ===
Contributors: veganist, beaulebens
Tags: prestashop, email, login, authentication, users, hash
Requires at least: 2.8
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will automatically rehash the passwords of users you have beforehand imported from a Prestashop database to your WP database.

== Description ==

This plugin will allow you to automatically rehash the passwords of users you have beforehand imported from a Prestashop database to your Wordpress database.

It allows users to authenticate via their email address, like on a Prestashop installation.

Upon login, the plugin will check the password entered by the user, against the value stored in the database. Using the COOKIE_KEY of the former Prestashop installation, it'll verify if the password is correct and, only in that case, it'll rehash the password using Wordpress' default hashing algorithm and insert the new password to the database. Then it'll try logging in.

In every other case, login will function normally, so if there is an error, it'll be returned.

In a while, every former Prestashop user you have imported to your WP DB will have their passwords securely rehashed.

Tested with Prestashop 1.3.5 and Wordpress 3.5.

== Installation ==

1. Unzip and upload `/prestashop-users-compat/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does the plugin import the user database of my Prestashop installation? =

No. You will have to do this yourself manually.

== Changelog ==

= 1.0 =
* Initial release
