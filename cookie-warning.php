<?php
/*
Plugin Name: Cookie warning
Plugin URI: http://majweb.co.uk/services/cookie-warning
Description: Asks users for their consent for using cookies or redirects them out of your site.
Version: 1.3
Author: Marie Manandise, MAJWeb
Author URI: http://majweb.co.uk
License: GPL2
*/


/*  Copyright 2011  Marie Manandise  (email : marie@majweb.co.uk)

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


//include main processing file (actions other than options setting)
include_once ( 'cookie-warning-options.php' );

add_action( 'wp_enqueue_scripts' , 'load_cookie_warning_scripts' );

//ajax call to clear cookies if decides out
add_action('wp_ajax_php_clear_cookies', 'php_clear_cookies');
add_action('wp_ajax_nopriv_php_clear_cookies', 'php_clear_cookies');

function get_cookie_defaults()
{
    global $cookie_warn_default_20105;
    $cookie_warn_default_20105 = array(
				   'warn_text' => esc_attr( "In order for this site to work properly, and in order to evaluate and improve the site we need to store small files (called cookies) on your computer.<br/> Over 90% of all websites do this, however, since the 25th of May 2011 we are required by EU regulations to obtain your consent first. What do you say?" ),
				   'redirect' => esc_attr( "http://google.com" ),
				   'ok_text' => esc_attr( "That's fine" ),
				   'notok_text' => esc_attr( "I don't agree" )
				   );
    return $cookie_warn_default_20105;
}

function load_cookie_warning_scripts(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style( 'cookie-style' , plugin_dir_url(__FILE__).'cookiewarning.css' );
    wp_enqueue_script( 'cookie-warning' , plugin_dir_url(__FILE__).'cookiewarning.js' );
    //pass options to JS script
    $options = get_option( 'cookiewarn_options' , get_cookie_defaults());
    $options_js = array(
		     'messageContent' => $options[ 'warn_text' ],
		     'redirectLink' => $options[ 'redirect' ],
		     'okText' => $options[ 'ok_text' ],
		     'notOkText' => $options[ 'notok_text' ],
		     'cookieName' => 'jsCookiewarning29Check',
		     'ajaxUrl' => site_url().'/wp-admin/admin-ajax.php'
		     );
    wp_localize_script( 'cookie-warning' , 'user_options' , $options_js);
}

function php_clear_cookies(){
    $avoid_del = $_REQUEST[ 'cookie' ];
    $pastdate = mktime(0,0,0,1,1,1970);
    $temp = $_COOKIE;
    foreach( $temp as $name => $value ){
	if ( $name != $avoid_del ) {
	    setcookie( $name , "" , $pastdate );
	}
    }
    echo json_encode( array( 'success' => true ) );
    die;
}

?>