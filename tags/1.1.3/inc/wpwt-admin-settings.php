<div class="wrap">
	<div id="icon-tools" class="icon32"></div><h2>World Travel Settings</h2>	
	<form method="post" action="options.php">
		<?php settings_fields( 'wpwt_settings' ); ?>
		<input type="hidden" name="wpwt-admin-submit" value="true" />
		<h3>General Settings</h3>
		<p>
			<label for="wpwt_settings[wpwt-introduction]" style="display:inline-block;width:10em">Introduction</label>
			<input size="30" maxlength="64" type="text" name="wpwt_settings[wpwt-introduction]" value="<?php echo $options['wpwt_introduction']; ?>" />
		</p>
		<h3>Schedule Settings</h3>
		<p>
			<label style="display:inline-block;width:10em">Hide Schedule</label>
			<input type="radio" name="wpwt_settings[wpwt-hide-schedule]" value="1" <?php if ( $options['wpwt_hide_schedule'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-hide-schedule]" style="display:inline-block;width:6em">Yes</label>
			<input type="radio" name="wpwt_settings[wpwt-hide-schedule]" value="0" <?php if ( ! $options['wpwt_hide_schedule'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-hide-schedule]" style="display:inline-block;width:6em">No</label>	
		</p>
		<p>
			<label style="display:inline-block;width:10em">Hide Previous</label>
			<input type="radio" name="wpwt_settings[wpwt-hide-previous]" value="1" <?php if ( $options['wpwt_hide_previous'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-hide-previous]" style="display:inline-block;width:6em">Yes</label>
			<input type="radio" name="wpwt_settings[wpwt-hide-previous]" value="0" <?php if ( ! $options['wpwt_hide_previous'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-hide-previous]" style="display:inline-block;width:6em">No</label>	
		</p>		
		<p>
			<label style="display:inline-block;width:10em">Schedule Link Text</label>
			<input size="30" maxlength="64" type="text" name="wpwt_settings[wpwt-show-schedule-text]" value="<?php echo $options['wpwt_show_schedule_text']; ?>" />
			<input size="30" maxlength="64" type="text" name="wpwt_settings[wpwt-hide-schedule-text]" value="<?php echo $options['wpwt_hide_schedule_text']; ?>" />
		</p>		
		<h3>Meetup Settings</h3>
		<p>
			<label style="display:inline-block;width:10em">Enabled</label>
			<input type="radio" name="wpwt_settings[wpwt-meetups-enabled]" value="1" <?php if ( $options['wpwt_meetups_enabled'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-meetups-enabled]" style="display:inline-block;width:6em">Yes</label>
			<input type="radio" name="wpwt_settings[wpwt-meetups-enabled]" value="0" <?php if ( ! $options['wpwt_meetups_enabled'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-meetups-enabled]" style="display:inline-block;width:6em">No</label>	
		</p>		
		<p>
			<label style="display:inline-block;width:10em">Email Alerts</label>
			<input type="radio" name="wpwt_settings[wpwt-send-email]" value="1" <?php if ( $options['wpwt_send_email'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-send-email]" style="display:inline-block;width:6em">Yes</label>
			<input type="radio" name="wpwt_settings[wpwt-send-email]" value="0" <?php if ( ! $options['wpwt_send_email'] ) { echo 'checked="checked"'; } ?> />
			<label for="wpwt_settings[wpwt-send-email]" style="display:inline-block;width:6em">No</label>	
		</p>
		<p>
			<label style="display:inline-block;width:10em">Meetup Link Text</label>
			<input size="30" maxlength="64" type="text" name="wpwt_settings[wpwt-lets-meetup-text]" value="<?php echo $options['wpwt_lets_meetup_text']; ?>" />
		</p>		
		<p class="submit">
			<input type="submit" class="button-primary" value="Save Settings" />
		</p>					
	</form>
	<table width="75%">
		<tr>
			<td width="50%" style="padding-right:12px;vertical-align:top">
				<h4>About the Plugin</h4>
				<p style="text-align:justify">The <a href="<?php echo GT_WPWT; ?>">WP World Travel</a> plugin enables your readers to view your travel plans and propose meetups along the way. We welcome you to visit the Globetrooper <a href="<?php echo GT_BLOG ?>">Travel Blog</a> to view our schedule and propose meetups with us too.</p>
				<h4>Installing the Plugin</h4>
				<p style="text-align:justify">Install and activate it on your <a href="<?php echo PLUGINS_DIR ?>">Plugins</a> page. Then, add your travel plans on the <a href="<?php echo WPWT_SCHEDULE ?>">Schedule</a> page. Finally, go to your <a href="<?php echo WIDGETS_DIR ?>">Widgets</a> page and drag the World Travel widget to one of your sidebars. </p>
			</td>
			<td width="50%" style="padding-left:12px;vertical-align:top">
				<h4>About Meetup Alerts</h4>
				<p style="text-align:justify">When someone proposes a meetup, the plugin attempts to send you an email. But email is fickle. If you don't receive email alerts, either your server is setup incorrectly or they are in your spam folder. Otherwise, you can see them on the <a href="<?php echo WPWT_MEETUPS ?>">Meetups</a> page.</p>
				<h4>Need Help? Want New Features?</h4>
				<p>Visit the <a href="<?php echo GT_WPWT; ?>">WP World Travel</a> page to read more about the plugin and to contact us. You can either leave a comment or use the <a href="<?php echo GT_CONTACT ?>">Contact</a> form. We're happy to help in any way we can.</p>
			</td>
		</tr>
	</table>
</div>