<?php
/*
Plugin Name: Prestashop user compatibility
Plugin URI: http://curlybracket.net/plugz/puc
Description: Prestashop user password rehasher
Author: Ulrike Uhlig
Version: 1.0
Author URI: http://curlybracket.net
*/

/*  Copyright 2012  Ulrike Uhlig  (email : u@curlybracket.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * @param string $user
 * @param string $username
 * @param string $password
 * @return Results of authenticating via wp_authenticate_username_password(), using the username found when looking up via email. Adds new WP hased password to database if a former Prestashop user identifies correctly.
 */

function pw_rehash( $user, $username, $password ) {
	global $wpdb;
	require_once( ABSPATH . 'wp-includes/class-phpass.php');

	// login via email or username. code by Beau Lebens
	$user = get_user_by( 'email', $username );
	if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status ) {
		$username = $user->user_login;
	}

	// This is the hashing function of Prestashop 1.3.5 : $ps_hashed_pw = md5($ps_salt.$plaintext_pw);
	$ps_salt = get_option('ps-cookie-salt');
	if(!empty($ps_salt)) {
		$wp_hashed_pw = $user->user_pass; // password stored in WPDB
		$plaintext_pw = $password;
		$ps_hashed_pw = md5($ps_salt.$plaintext_pw);

		// this means that the user's password in the DB is an old Prestashop password
		// if the password is correct, we rehash and update it
		if($ps_hashed_pw == $user->user_pass) {
			wp_set_password( $plaintext_pw, $user->id );
		}
	}

	return wp_authenticate_username_password( null, $username, $password );
}
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'pw_rehash', 20, 3 );

/**
 * Add options page to settings menu
 */

add_action('admin_menu', 'prestashop_user_compat_menu');

function prestashop_user_compat_menu() {
    add_submenu_page( 'options-general.php', 'Prestashop User Compat', 'Prestashop User Compat', 'manage_options', 'prestashop_user_compat_page', 'prestashop_user_compat_page_callback' );
	add_action( 'admin_init', 'register_puc_settings' );
}

function prestashop_user_compat_page_callback() {
	echo '<div class="wrap">';
	screen_icon();
    echo '<h2>Options for Prestashop user compatibility</h2>';
 cho '<p>Enter here the value of the COOKIE_KEY, found in config/settings.inc.php of your former Prestashop installation.<br />This should look something like CtUKJZE31LzULMLcsspKhF2IrZpOShBe56B42vH15PMMt06WvEDUj5HX</p><form method="post" action="options.php">';
	settings_fields( 'prestashop-user-compat-group' );
	echo '<input type="text" name="ps-cookie-salt" value="'.get_option('ps-cookie-salt').'" style="width: 70%;" />';
	submit_button();
	echo '</form></div>';
}

function register_puc_settings() {
	register_setting( 'prestashop-user-compat-group', 'ps-cookie-salt' );
}
?>
