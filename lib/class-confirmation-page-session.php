<?php

class Confirmation_Page_Session{
  
  /**
   * Plugin instance.
   *
   * @see get_instance()
   * @type object
   */
  protected static $instance = NULL;

  public function __construct($use_php_sessions) {

    // Check if the instance already has $use_php_sessions set
    $this->use_php_sessions = $use_php_sessions ?: $this->use_php_sessions;

    // // Call the init function. We do things this way so that the code invoked by init can happen
    // // at a particular WordPress hook, rather than when this class is instantiated
    // add_action( 'plugins_loaded', array( $this, 'init' ), -1 );

  }

  public function init() {
    // Set our session preferences
    if( $this->use_php_sessions ) {
      // Check if the session is already initialised
      $has_session = session_status() == PHP_SESSION_ACTIVE;
      if( !$has_session ){
        session_start();
      } 
      
      // The session is now active
      $this->session = &$_SESSION;
    } else {
      $this->session = WP_Session::get_instance();
    }

    // Hook onto the gateway booking to set the session
    add_action( 'eventorganiser_pre_gateway_booking', array($this, 'set_booking_id'));

    return $this->session;
  }

  public function set_booking_id($booking_id){

    $_SESSION['test'] = 'I tried to set the booking id which is ' . $booking_id . ' at ' . date('Hi');

    $this->session['eo_confirmation_page_booking_id'] = $booking_id; 

    return $this->session;
  }

  public function get_booking_id(){

    // return $this->session['eo_confirmation_page_booking_id'];

    if(isset($this->session['eo_confirmation_page_booking_id'])){
      return $this->session['eo_confirmation_page_booking_id'];
    }
    else{
      return 'Not yet set';
    }
  }

  /**
   * Access this plugin’s working instance
   *
   * @wp-hook plugins_loaded
   * @since   2012.09.13
   * @return  object of this class
   */
  
  // As per https://gist.github.com/toscho/3804204
  public static function get_instance($use_php_sessions = null){

    NULL === self::$instance and self::$instance = new self($use_php_sessions);
    return self::$instance;

  }

  public function display_no_session_error(){
    ?>
    <div class="error">
        <p><?php _e( 'EO Confirmation Page Session: neither <a href="https://github.com/ericmann/wp-session-manager">WP_Session</a> or PHP $_SESSION exist.', 'eo-confirmation-page-session' ); ?></p>
    </div>
    <?php
  }

}