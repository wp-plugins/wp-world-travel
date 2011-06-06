<div class="wpwt-widget">
	<div id="wpwt-current-location">
		<?php echo trim( $settings['wpwt_introduction'] ); ?>&nbsp;<span class="wpwt-title"><?php echo $current_location ?></span>
	</div>
	<ul>
		<li><a id="wpwt-schedule-link" href="javascript:wpwt_schedule_toggle()">View My Travel Schedule</a></li>
	</ul>
	<div id="wpwt-schedule">
		<div id="wpwt-meetup">
			Meetup in&nbsp;
			<select id="wpwt-meetup-location">
				<?php foreach( $locations as $location ) { ?>
					<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
				<?php } ?>
			</select>
		  <form method="post"	action="#">
		    <label for="wpwt-meetup-name">Name</label>
		    <input class="wpwt-meetup-text" type="text" id="wpwt-meetup-name" />
		    <label for="wpwt-meetup-email">Email</label>
		    <input class="wpwt-meetup-text" type="text" id="wpwt-meetup-email" />
		    <label for="wpwt-meetup-message">Proposed Meetup</label>
		    <textarea rows="3" class="wpwt-meetup-text" id="wpwt-meetup-message"></textarea>
		    <input type="button" value="Send Meetup Request" id="wpwt-meetup-submit" onclick="javascript:wpwt_meetup_send( '<?php echo AJAX_DIR ?>' )" />
		    <img id="wpwt-meetup-sending" src="<?php echo BLOG_DIR ?>/wp-admin/images/loading.gif" alt="Sending" />
		    <a class="wpwt-meetup-close" href="javascript:wpwt_meetup_close()">Close</a>
			</form>
		</div>
		<div id="wpwt-meetup-success">
			<div>Success. Woo hoo!</div>
		</div>
		<?php $i = 0; ?>
		<?php foreach( $legs as $key=>$value ) { ?>			
			<div class="wpwt-flag">					
				<img src="<?php echo WPWT_DIR . 'img/' . strtolower( $value['wpwt_country_code'] ) ?>.png" alt="<?php echo $value['wpwt_country_name'] ?>" /><a href="http://globetrooper.com/<?php echo strtolower( str_replace( ' ', '-', $value['wpwt_country_name'] ) ); ?>"><?php echo $value['wpwt_country_name'] ?></a>
			</div>
			<div class="wpwt-title">
				<?php echo $value['wpwt_place'] . ', ' . $value['wpwt_country_name']; ?>
			</div>
			<ul>
				<li><?php echo date( 'd-M-Y', $value['wpwt_from_date'] ) ?> to <?php echo date( 'd-M-Y', $value['wpwt_to_date'] ) ?></li>
				<li><a id="wpwt-meet-link" href="javascript:wpwt_meetup_toggle( '<?php echo $i ?>' )">Let's Meetup Here</a></li>
			</ul>
			<?php $i++; ?>
		<?php } ?>		
	</div>
</div>