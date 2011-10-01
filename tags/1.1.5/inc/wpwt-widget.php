<div class="wpwt-widget">
	<?php if( ! empty( $legs ) ) : ?>
    	<p id="wpwt-current-location">
    		<?php echo $settings['wpwt_introduction']; ?>&nbsp;<span class="wpwt-title"><?php echo $current_location ?></span>
    	</p>
    	<?php if ($settings['wpwt_hide_schedule'] == true): ?>
    	<ul>
    		<li><a id="wpwt-schedule-link" href="javascript:wpwt_schedule_toggle( '<?php echo $settings['wpwt_show_schedule_text']; ?>', '<?php echo $settings['wpwt_hide_schedule_text']; ?>' )"><?php echo $settings['wpwt_show_schedule_text']; ?></a></li>
    	</ul>
    	<?php endif ?>
    	<div id="wpwt-schedule" style="display:<?php echo $settings['wpwt_hide_schedule'] == true ? 'none' : 'block'; ?>">
    		<div id="wpwt-meetup">
    			<form method="post"	action="#">
    				<label for="wpwt-meetup-location"><?php echo $settings['wpwt_lets_meetup_text']; ?></label>
    				<select id="wpwt-meetup-location">
    					<?php foreach( $locations as $location ) { ?>
    						<option value="<?php echo $location; ?>"><?php echo $location; ?></option>
    					<?php } ?>
    				</select>
    		    <label for="wpwt-meetup-name"><?php echo __( 'Name' ) ?></label>
    		    <input class="wpwt-meetup-text" type="text" id="wpwt-meetup-name" />
    		    <label for="wpwt-meetup-email"><?php echo __( 'E-mail' ) ?></label>
    		    <input class="wpwt-meetup-text" type="text" id="wpwt-meetup-email" />
    		    <label for="wpwt-meetup-message"><?php echo __( 'Message' ) ?></label>
    		    <textarea rows="3" class="wpwt-meetup-text" id="wpwt-meetup-message"></textarea>
    		    <input type="button" value="<?php echo __( 'Submit' ) ?>" id="wpwt-meetup-submit" onclick="javascript:wpwt_meetup_send( '<?php echo AJAX_DIR ?>' )" />
    		    <img id="wpwt-meetup-sending" src="<?php echo BLOG_DIR ?>/wp-admin/images/loading.gif" alt="Sending" />
    		    <a class="wpwt-meetup-close" href="javascript:wpwt_meetup_close()"><?php echo __( 'Close' ) ?></a>
    			</form>
    		</div>
    		<div id="wpwt-meetup-success">
    			<div><?php echo __( 'Done' ) ?></div>
    		</div>
    		<?php $i = 0; ?>
    		<?php foreach( $legs as $key=>$value ) { ?>			
    			<div class="wpwt-flag">				
    				<a style="background:#fff url('<?php echo WPWT_DIR . 'img/' . strtolower( $value['wpwt_country_code'] ) ?>.png')" href="http://globetrooper.com/<?php echo strtolower( str_replace( ' ', '-', $value['wpwt_country_name'] ) ); ?>">
    					<img src="<?php echo WPWT_DIR . 'img/' . strtolower( $value['wpwt_country_code'] ) ?>.png" alt="What To Do In <?php echo $value['wpwt_country_name'] ?>" />	
    					<?php echo $value['wpwt_country_name'] ?>
    				</a>
    			</div>
    			<div class="wpwt-title">
    				<?php 
    					if ( empty( $value['wpwt_place'] ) ) {
    						echo $value['wpwt_country_name'];
    					} else {
    						echo $value['wpwt_place'] . ', ' . $value['wpwt_country_name'];
    					} 
    				?>
    			</div>
    			<ul>
    				<li>
    					<?php echo date_i18n( 'j M Y', $value['wpwt_from_date'] ); ?>
    					&nbsp;-&nbsp;
    					<?php echo date_i18n( 'j M Y', $value['wpwt_to_date'] ); ?>
    				</li>
    				<?php if ($settings['wpwt_meetups_enabled']): ?>
    					<li><a id="wpwt-meet-link" href="javascript:wpwt_meetup_toggle( '<?php echo $i ?>' )"><?php echo $settings['wpwt_lets_meetup_text']; ?></a></li>
    				<?php endif; ?>
    			</ul>
    			<?php $i++; ?>
    		<?php } ?>
    	</div>
	<?php else : ?>
		<p>Travel schedule not configured.</p>
	<?php endif ?>
	<div class="wpti-sig" style="color:#eee;height:0;overflow:hidden;width:0;"><a href="http://globetrooper.com">Group Travel</a></div>
</div>