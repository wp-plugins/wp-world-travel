function wpwt_schedule_toggle() {

	var schedule = $( '#wpwt-schedule' );
	var text = $( '#wpwt-schedule-link' );
	var meetup = $( '#wpwt-meetup' );
	var success = $( '#wpwt-meetup-success' );
	
	if( schedule.css( 'display' ) == 'block' ) {
		schedule.slideUp( 'fast' );
		text.text( 'View My Travel Schedule' );
  }
	else {
		meetup.hide();
		success.hide();
		text.text( 'Hide My Travel Schedule' );
		schedule.slideDown( 'fast' );
	}
	
}

function wpwt_meetup_toggle( index ) {

	var schedule = $( '#wpwt-schedule' );
	var text = $( '#wpwt-schedule-link' );
	var meetup = $( '#wpwt-meetup' );
	var location = $( '#wpwt-meetup-location' );
	var name = $( '#wpwt-meetup-name' );
	var email = $( '#wpwt-meetup-email' );
	var message = $( '#wpwt-meetup-message' );
	var success = $( '#wpwt-meetup-success' );
	
	if( meetup.css( 'display' ) == 'none' ) {
  	meetup.slideDown( 'fast' );
  	success.hide();	  	
		location.attr( 'selectedIndex', index );		
		
		name.removeClass( 'wpwt-form-error' );
		email.removeClass( 'wpwt-form-error' );
		message.removeClass( 'wpwt-form-error' );
		
		message.val( '' );
	}
	else {
  	location.attr( 'selectedIndex', index );
	}
	
}

function wpwt_meetup_success() {

	var meetup = $( '#wpwt-meetup' );
	var success = $( '#wpwt-meetup-success' );
	var button = $( '#wpwt-meetup-submit' );
	var sending = $( '#wpwt-meetup-sending' );	
	
  meetup.hide();
  success.show();
  
	button.removeAttr( 'disabled' );
	sending.hide();	  
  
  setTimeout( function() { success.slideUp( 'fast' ); }, 2000 );

}

function wpwt_meetup_send( admin_ajax ) {

	var name = $( '#wpwt-meetup-name' );
	var email = $( '#wpwt-meetup-email' );
	var message = $( '#wpwt-meetup-message' );
	var location = $( '#wpwt-meetup-location' );
	
	if( name.val().length > 0 && wpwt_is_email_valid( email.val() ) && message.val().length > 0 ) {
	
		var button = $( '#wpwt-meetup-submit' );
		var sending = $( '#wpwt-meetup-sending' );	
		
		button.attr( 'disabled', 'disabled' );
		sending.show();
		
		jQuery.post( admin_ajax , { 
		
			'action': 'wpwt_meetup_process',  
		 	'wpwt_name': name.val(),
		 	'wpwt_email': email.val(),
		 	'wpwt_message': message.val(),
			'wpwt_location': location.val(),		 
		 	'success': function() { wpwt_meetup_success(); }
		 
		 } );		
	
	} else {	
	
		if( name.val().length < 1 ) {
			name.addClass( 'wpwt-form-error' );
		}
		else {
			name.removeClass( 'wpwt-form-error' );
		}
		
		if( ! wpwt_is_email_valid( email.val() ) ) {
			email.addClass( 'wpwt-form-error' );
		}
		else {
			email.removeClass( 'wpwt-form-error' );
		}
				
		if( message.val().length < 5 ) {
			message.addClass( 'wpwt-form-error' );
		}
		else {
			message.removeClass( 'wpwt-form-error' );
		}		
	
	}

}

function wpwt_meetup_close() {

	var meetup = $( '#wpwt-meetup' );
	
	meetup.slideUp( 'fast' );

}

function wpwt_is_email_valid( emailAddress ) {

	var pattern = new RegExp( /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i );
	
	return( pattern.test( emailAddress ) );
	
}