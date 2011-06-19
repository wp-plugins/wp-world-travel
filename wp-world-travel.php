<?php
/*
Plugin Name: WP World Travel
Plugin URI: http://globetrooper.com/notes/wordpress-world-travel-plugin/
Version: 1.0.4
Author: <a href="http://globetrooper.com/">Todd Sullivan</a> of <a href="http://globetrooper.com">Globetrooper</a>
Description: Show your current location and travel schedule (or travel itinerary) in the sidebar of your travel blog. Readers can also propose meetups at each of your future destinations.
*/

define( 'BLOG_DIR', get_bloginfo( 'url' ) );
define( 'PLUGINS_DIR', admin_url( 'plugins.php' ) );
define( 'WIDGETS_DIR', admin_url( 'widgets.php' ) );
define( 'AJAX_DIR', admin_url( 'admin-ajax.php' ) );

define( 'WPWT_DIR', plugin_dir_url( __FILE__ ) );
define( 'WPWT_SETTINGS', admin_url( 'admin.php?page=wpwt-settings' ) );
define( 'WPWT_SCHEDULE', admin_url( 'admin.php?page=wpwt-schedule' ) );
define( 'WPWT_MEETUPS', admin_url( 'admin.php?page=wpwt-meetups' ) );

define( 'GT_HOME', 'http://globetrooper.com' );
define( 'GT_BLOG', 'http://globetrooper.com/notes/' );
define( 'GT_CONTACT', 'http://globetrooper.com/general/contact/' );
define( 'GT_WPWT', 'http://globetrooper.com/notes/wordpress-world-travel-plugin/' );

register_activation_hook( __FILE__,  array( 'WP_World_Travel', 'wpwt_activation' ) );

if ( ! class_exists( 'WP_World_Travel' ) ) {

	class WP_World_Travel extends WP_Widget {
	
		function WP_World_Travel() {
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'WP_World_Travel' , WPWT_DIR . 'js/wp-world-travel.js' );
			wp_enqueue_style( 'WP_World_Travel' , WPWT_DIR . 'css/wp-world-travel.css' );
							
			$widget_ops = array( 'classname' => 'wp-world-travel', 'description' => 'Show your current location and travel itinerary in the sidebar. Readers can also propose meetups at each future destination.' );
			$this->WP_Widget( 'WP_World_Travel', '&nbsp;World Travel', $widget_ops );
			
			WP_World_Travel::wpwt_setup_defaults();
					
		}
		
		function wpwt_activation() {
		
			WP_World_Travel::wpwt_setup_defaults();						
		
		}
		
		function wpwt_setup_defaults() {
		
			$options_settings = get_option( 'wpwt_settings' );
			if( empty( $options_settings['wpwt_introduction'] ) )
				$options_settings['wpwt_introduction'] = 'I\'m currently in';
			if( empty( $options_settings['wpwt_show_schedule_text'] ) )				
				$options_settings['wpwt_show_schedule_text'] = 'View My Travel Itinerary';	
			if( empty( $options_settings['wpwt_hide_schedule_text'] ) )	
				$options_settings['wpwt_hide_schedule_text'] = 'Hide My Travel Itinerary';
			if( empty( $options_settings['wpwt_lets_meetup_text'] ) )	
				$options_settings['wpwt_lets_meetup_text'] = 'Let\'s Meetup Here';
			if( ! is_bool( $options_settings['wpwt_hide_schedule'] ) )	
				$options_settings['wpwt_hide_schedule'] = true;
			if( ! is_bool( $options_settings['wpwt_send_email'] ) )	
				$options_settings['wpwt_send_email'] = true;
			if( ! is_bool( $options_settings['wpwt_meetups_new'] ) )	
				$options_settings['wpwt_meetups_new'] = false;
				
			update_option( 'wpwt_settings', $options_settings );
			
			$options_schedule = get_option( 'wpwt_schedule', false );
			if( empty( $options_schedule ) ) {
		
				$new_leg = array();
				$new_leg['wpwt_from_date'] = mktime( 12, 0, 0, 1, 1, date( 'Y' ) );
				$new_leg['wpwt_to_date'] = mktime( 12, 0, 0, 1, 1, date( 'Y' ) + 1 );
				$new_leg['wpwt_place'] = 'Test Location';
				$new_leg['wpwt_country_code'] = 'AU';
				$new_leg['wpwt_country_name'] = 'Australia';
				
				$options_schedule['leg_' . date( 'U' )] = $new_leg;
									
				update_option( 'wpwt_schedule', $options_schedule );
			
			}
			
			$options_meetup = get_option( 'wpwt_meetups', false );
			if( empty( $options_meetup ) ) {
			
				$today = date( 'U' );
				
				$new_meetup = array();
				$new_meetup['wpwt_date'] = $today;
				$new_meetup['wpwt_location'] = 'Sydney, Australia';
				$new_meetup['wpwt_name'] = 'Todd and Lauren';
				$new_meetup['wpwt_email'] = 'team@globetrooper.com';
				$new_meetup['wpwt_message'] = 'Test Meetup: although we really would love to catch up and show you around town. See our itinerary on the Globetrooper blog.';
				
				$options_meetup['meetup_' . $today . '_team@globetrooper.com'] = $new_meetup;
				
				update_option( 'wpwt_meetups', $options_meetup );
			
			}					
		
		}		
		
	  function form( $instance ) {
	  
			$instance = wp_parse_args( (array) $instance, array( 'title' => 'Current Location' ) );
			$title = strip_tags( $instance['title'] );
			
			require_once 'inc/wpwt-widget-admin.php';
			
	  }
	  
	  function update( $new_instance, $old_instance ) {

			$instance = $old_instance;
			$instance['title'] = $new_instance['title'] != '' ? strip_tags( $new_instance['title'] ) : 'Current Location';
			
			return $instance;
			
	  }
 
	  function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			
			echo $before_widget;
			
			$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
			$options = get_option( 'wpwt_schedule' );
			$settings = get_option( 'wpwt_settings' );
			
			$legs = array();
			$locations = array();
			$current_location = '';
			
			array_multisort( $options, SORT_ASC );
			
			if( ! empty( $options ) ) {
			
				foreach( $options as $leg ) {
					
					if ( $leg['wpwt_to_date'] > date( 'U' ) ) { 
					
						if ( empty( $leg['wpwt_place'] ) ) {
						
							$location = $leg['wpwt_country_name'];
							
						} else {
						
							$location = $leg['wpwt_place'] . ', ' . $leg['wpwt_country_name'];
							
						}
						
						if ( $leg['wpwt_from_date'] < date( 'U' ) ) { 
							$current_location = $location;
						}
						
						$legs[] = $leg;
						$locations[] = $location;
						
					}
					
				}
							
			} 
			
	  	require_once 'inc/wpwt-widget.php';
	  	
			echo $after_widget;
			
	  }
	  
		function wpwt_ajax_header() {
		
		  require_once 'inc/wpwt-ajax-header.php';
		  
		}
		
		function wpwt_meetup_process() {
			
			$options = get_option( 'wpwt_meetups' );
			$today = date( 'U' );
			
			$new_meetup = array();
			$new_meetup['wpwt_date'] = $today;
			$new_meetup['wpwt_location'] = $_POST['wpwt_location'];
			$new_meetup['wpwt_name'] = $_POST['wpwt_name'];
			$new_meetup['wpwt_email'] = $_POST['wpwt_email'];
			$new_meetup['wpwt_message'] = $_POST['wpwt_message'];
			
			$options['meetup_' . $today . '_' . $_POST['wpwt_email']] = $new_meetup;
			
			update_option( 'wpwt_meetups', $options );			
			
			WP_World_Travel::wpwt_meetups_new( true );
			
			$options_settings = get_option( 'wpwt_settings' );
			
			global $wpdb;
			
			if( $options_settings['wpwt_send_email'] == true ) {
			
				$recipient = get_option( 'blogname' ) . " <" . get_option( 'admin_email' ) . ">";
				$subject = "Meetup Proposal from " . $_POST['wpwt_name'];
				
				/* Must use double quotes for newline to work. */
				$message = "This is a Meetup Proposal from the Globetrooper WP World Travel Plugin.\n\n";
				$message .= "From Name: " . $_POST['wpwt_name'] . "\n";
				$message .= "From Email: " . $_POST['wpwt_email'] . "\n\n";
				$message .= "Location: " . $_POST['wpwt_location'] . "\n";
				$message .= "Proposed Meetup: " . $_POST['wpwt_message'] . "\n\n";
				$message .= "Group Trips: http://globetrooper.com/" . str_replace( ' ', '-', trim( strtolower(  substr( $_POST['wpwt_location'], - ( strlen( $_POST['wpwt_location'] ) - strpos( $_POST['wpwt_location'], ',' ) - 1 ) ) ) ) ) . "\n";
				
				$sitename = strtolower( $_SERVER['SERVER_NAME'] );
				
				if ( substr( $sitename, 0, 4 ) == 'www.' ) {
					
					$sitename = substr( $sitename, 4 );
				
				}
				
				$from_email = 'wordpress@' . $sitename;	
				
				$from_email = 'test@globetrooper.com';
				
				$header = "";
				$header .= "From: " . $_POST['wpwt_name'] . " <" . $from_email . ">\n";
				$header .= "Reply-To: " . $_POST['wpwt_email'] . "\n";
				$header .= "Content-type: text/plain; charset=" . get_option( 'blog_charset' );
				$header .= "Return-Path: " . $from_email . "\n";
				
				@wp_mail( $recipient, $subject, $message, $header );
			
			}
			
		  echo 'success';  	  

		}  	

	  function wpwt_admin_settings() {

			$options = get_option( 'wpwt_settings' );
	  
	  	require_once 'inc/wpwt-admin-settings.php';
	  
	  }
	  
		function wpwt_admin_settings_process( $input ) {
			
			if( isset( $_POST['wpwt-admin-submit'] ) ) {
			
				$options = get_option( 'wpwt_settings' );
				$options['wpwt_introduction'] = trim( $input['wpwt-introduction'] );
				$options['wpwt_show_schedule_text'] = $input['wpwt-show-schedule-text'];
				$options['wpwt_hide_schedule_text'] = $input['wpwt-hide-schedule-text'];
				$options['wpwt_lets_meetup_text'] = $input['wpwt-lets-meetup-text'];
				$options['wpwt_hide_schedule'] = (bool)$input['wpwt-hide-schedule'];
				$options['wpwt_send_email'] = (bool)$input['wpwt-send-email'];
				
				return $options;
				
			} else {		
			
				return $input;
				
			}			
		
		} 	  
	  
	  function wpwt_admin_schedule() {

			global $wp_locale;
			$options = get_option( 'wpwt_schedule' );
	  
	  	require_once 'inc/wpwt-admin-schedule.php';
	  
	  }
	  
		function wpwt_admin_schedule_process( $input ){
			
			$options = get_option( 'wpwt_schedule' );
			
			if( isset( $_POST['wpwt-delete'] ) ) {
				
				unset( $options[$_POST['wpwt-delete']] );
				
			} else {
				
				$from_year = is_numeric( $input['wpwt-from-year'] ) ? $input['wpwt-from-year'] : date( 'Y' );
				$from_day = is_numeric( $input['wpwt-from-day'] ) ? $input['wpwt-from-day'] : 1;
				$to_year = is_numeric( $input['wpwt-to-year'] ) ? $input['wpwt-to-year'] : date( 'Y' ) + 1;
				$to_day = is_numeric( $input['wpwt-to-day'] ) ? $input['wpwt-to-day'] : 1;
				
				$new_leg = array();
				$new_leg['wpwt_from_date'] = mktime( 12, 0, 0, $input['wpwt-from-month'], $from_day, $from_year );
				$new_leg['wpwt_to_date'] = mktime( 12, 0, 0, $input['wpwt-to-month'], $to_day, $to_year );
				$new_leg['wpwt_place'] = $input['wpwt-place'];
				$new_leg['wpwt_country_code'] = substr( $input['wpwt-country'], 0, 2 );
				$new_leg['wpwt_country_name'] = substr( $input['wpwt-country'], - ( strlen( $input['wpwt-country'] ) - 3 ) );
				
				$options['leg_' . date( 'U', $new_leg['wpwt_from_date'])] = $new_leg;
				
			}
			
			return $options;
		
		} 	  
	  
	  function wpwt_admin_meetups() {

			global $wp_locale;
			$options = get_option( 'wpwt_meetups' );
	  
	  	require_once 'inc/wpwt-admin-meetups.php';
	  
	  } 

		function wpwt_admin_meetups_process( $input ){
		
			if( isset( $_POST['wpwt-delete'] ) ) {
			
				$options = get_option( 'wpwt_meetups' );
				unset( $options[$_POST['wpwt-delete']] );
				
				return $options;			
				
			} else {		
			
				return $input;
				
			}
		
		}
		
		function wpwt_meetups_new( $status = null ) {
		
			$options_settings = get_option( 'wpwt_settings' );
			
			if( isset( $status ) ) {

				$options_settings['wpwt_meetups_new'] = $status;
				update_option( 'wpwt_settings', $options_settings );
			
			} else {
			
				return $options_settings['wpwt_meetups_new'];
			
			}			
		
		}
		
		function wpwt_meetups_alert() {
			
			if ( WP_World_Travel::wpwt_meetups_new() && ! preg_match( '/wpwt-meetups/i', strtolower( $_SERVER["REQUEST_URI"] ) ) ) {			
			
				require_once 'inc/wpwt-meetups-new.php';
			
			} else {
			
				WP_World_Travel::wpwt_meetups_new( false );
			
			}
		
		}			    
		
		function wpwt_admin_menu() {
		
			add_menu_page( 'Globetrooper', 'Globetrooper', 'manage_options', 'wpwt-settings', '' );
			add_submenu_page( 'wpwt-settings', 'Manage Settings', 'Settings', 'manage_options', 'wpwt-settings', array( 'WP_World_Travel', 'wpwt_admin_settings' ) );
			add_submenu_page( 'wpwt-settings', 'Manage Schedule', 'Schedule', 'manage_options', 'wpwt-schedule', array( 'WP_World_Travel', 'wpwt_admin_schedule' ) );
			add_submenu_page( 'wpwt-settings', 'Manage Meetups', 'Meetups', 'manage_options', 'wpwt-meetups', array( 'WP_World_Travel', 'wpwt_admin_meetups' ) );
			
		}

		function wpwt_load_options() {

		  register_setting( 'wpwt_settings', 'wpwt_settings', array( 'WP_World_Travel', 'wpwt_admin_settings_process' ) );
		  register_setting( 'wpwt_schedule', 'wpwt_schedule', array( 'WP_World_Travel', 'wpwt_admin_schedule_process' ) );
		  register_setting( 'wpwt_meetups', 'wpwt_meetups', array( 'WP_World_Travel', 'wpwt_admin_meetups_process' ) );

		}
		
	  function wpwt_load_widget() {
	  
			register_widget( 'WP_World_Travel' );
		
		}	
	  	
	}

	/* Ajax actions to process meetup form in widget. */
	add_action( 'wp_ajax_wpwt_meetup_process', array( 'WP_World_Travel', 'wpwt_meetup_process' ) );
	add_action( 'wp_ajax_nopriv_wpwt_meetup_process', array( 'WP_World_Travel', 'wpwt_meetup_process' ) );
		
	/* Admin actions for entire plugin. */
	add_action( 'admin_menu', array( 'WP_World_Travel', 'wpwt_admin_menu' ) );
	add_action( 'admin_init', array( 'WP_World_Travel', 'wpwt_load_options' ) );
	add_action( 'widgets_init', array( 'WP_World_Travel', 'wpwt_load_widget' ) );

	/* Admin panel alert for new meetups, in case email isn't working. */
	add_action( 'admin_notices', array( 'WP_World_Travel', 'wpwt_meetups_alert' ) );
}

?>