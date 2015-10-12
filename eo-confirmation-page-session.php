<?php
/**
 * Plugin Name: Event Organiser Confirmation Page Session
 * Description: Stores user's last completed Event Oragniser booking in a WP_Session to allow access to booking meta on a confirmation page.
 * Version: 0.2
 * Author: Proper Design
 * Author URI: http://properdesign.rs
 * License: GPL2
 * Text Domain: eo-confirmation-page-session
 * Domain Path: /languages
 *
 * Acknowledgements: 
 * Eric Mann's WP_Session plugin https://github.com/ericmann/wp-session-manager
 * Stephen Harris and Event Organiser for providing help getting this going http://wp-event-organiser.com
 * Pippin Williamson for his WP_Session wrapper class https://github.com/easydigitaldownloads/Easy-Digital-Downloads/blob/master/includes/class-edd-session.php
 */

/*  Copyright 2015  Proper Design  (email : hello@properdesign.rs)

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

/*
 * The following requries Event Organiser Pro 1.4+
 * Event Organiser Pro requires Event Organiser 2.7+
 */

add_action( 'plugins_loaded', '_eventorganiser_load_eo_confirmation_page_session' );

function _eventorganiser_load_eo_confirmation_page_session(){

  if ( ! defined( 'EVENT_ORGANISER_PRO_DIR' ) || version_compare( '1.9', EVENT_ORGANISER_PRO_VER ) > 0 ){
    return;
  }

  // Include Eric Mann's WP Session Manager
  // This isn't working for now as requiring it directly like this produces a 'headers already sent' error in PHP, which doesn't happen if
  // the plugin is loaded as normal in WordPress (rather than included as a library here)
  // require(dirname(__FILE__) . '/inc/wp-session-manager/wp-session-manager.php');

  // require(dirname(__FILE__) . '/lib/confirmation-page-session.php');    
  require(dirname(__FILE__) . '/lib/class-confirmation-page-session.php');
  require(dirname(__FILE__) . '/lib/shortcodes.php');

  // Load text domain
  load_plugin_textdomain( 'eo-confirmation-page-session', FALSE, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

  // Check if wp_session_manager exists. If it does, use it. If it doesn't, fall back to $_SESSSION. If neither exist, display an error
  if ( class_exists( 'WP_Session' ) ) {
    //Don't use PHP sessions as WP_Session exists
    Confirmation_Page_Session::get_instance(false)->init();
  }
  elseif( session_status() === PHP_SESSION_NONE || session_status() === PHP_SESSION_ACTIVE){
    // WP_Session doesn't exist, but we can use $_SESSION
    Confirmation_Page_Session::get_instance(true)->init();
  }
  else{
    //We have neither WP_Session or $_SESSION. Oh dear. Display an error
    add_action( 'admin_notices', array(Confirmation_Page_Session::get_instance(), 'display_no_session_error') );
  }

}
