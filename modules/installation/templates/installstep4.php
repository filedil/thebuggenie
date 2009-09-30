<?php include_template('installation/header'); ?>
<div class="installation_box">
	<?php if (isset($error)): ?>
		<div class="error"><?php echo nl2br($error); ?></div>
		<h2>An error occured</h2>
		<div style="font-size: 13px;">An error occured and the installation has been stopped. Please try to fix the error based on the information above, then click back, and try again.<br>
		If you think this is a bug, please report it in our <a href="http://b2.thebuggenie.com" target="_new">online bug tracker</a>.</div>
	<?php else: ?>
		<div class="ok">
			All settings were loaded successfully
		</div>
		<h2 style="margin-top: 10px;">Enabling functionality</h2>
		The Bug Genie consists of the B2 framework, and a set of modules. Some modules are used for "core" functionality, such as searching, email notifications, and messaging - while
		others provide extra functionality such as SVN (subversion) integration, articles, billboards and calendar.<br>
		<br>
		Please select which modules to enable here, before pressing "Continue":<br>
		<i>(You can always enable / disable this functionality from the configuration center after the installation is completed)</i>
		<form accept-charset="utf-8" action="index.php" method="post" id="bugs_settings">
			<input type="hidden" name="step" value="5">
			<fieldset>
				<legend>The Bug Genie modules</legend>
				<dl class="install_list">
					<dt>
						<strong>Enable outgoing email</strong><br>
						Enables functionality that sends out emails
					</dt>
					<dd>
						<input type="radio" name="modules[mailnotification]" value="1" id="modules_mailnotification_yes" checked="checked"><label for="modules_mailnotification_yes" style="margin-right: 5px;">Yes</label>
						<input type="radio" name="modules[mailnotification]" value="0" id="modules_mailnotification_no"><label for="modules_mailnotification_no">No</label>
					</dd>
					<dt>
						<strong>Enable messaging</strong><br>
						Enables functionality that lets users send messages to eachother
					</dt>
					<dd>
						<input type="radio" name="modules[messages]" value="1" id="modules_messages_yes" checked="checked"><label for="modules_messages_yes" style="margin-right: 5px;">Yes</label>
						<input type="radio" name="modules[messages]" value="0" id="modules_messages_no"><label for="modules_messages_no">No</label>
					</dd>
					<dt>
						<strong>Enable calendar</strong><br>
						Enables calendar functionality
					</dt>
					<dd>
						<input type="radio" name="modules[calendar]" value="1" id="modules_calendar_yes" checked="checked"><label for="modules_calendar_yes" style="margin-right: 5px;">Yes</label>
						<input type="radio" name="modules[calendar]" value="0" id="modules_calendar_no"><label for="modules_calendar_no">No</label>
					</dd>
					<dt>
						<strong>Enable articles &amp; billboards</strong><br>
						Enables functionality that lets you create articles and billboards 
					</dt>
					<dd>
						<input type="radio" name="modules[publish]" value="1" id="modules_publish_yes" checked="checked"><label for="modules_publish_yes" style="margin-right: 5px;">Yes</label>
						<input type="radio" name="modules[publish]" value="0" id="modules_publish_no"><label for="modules_publish_no">No</label>
					</dd>
					<dt>
						<strong>Enable subversion integration</strong><br>
						Enables functionality that makes it possible to integrate with SVN hooks
					</dt>
					<dd>
						<input type="radio" name="modules[svn_integration]" value="1" id="modules_svn_integration_yes" checked="checked"><label for="modules_svn_integration_yes" style="margin-right: 5px;">Yes</label>
						<input type="radio" name="modules[svn_integration]" value="0" id="modules_svn_integration_no"><label for="modules_svn_integration_no">No</label>
					</dd>
				</dl>
			</fieldset>
			<div style="padding-top: 20px; clear: both; text-align: center;">
				<label for="continue_button" style="font-size: 13px; margin-right: 10px;">Click this button to continue and enable the selected modules</label>
				<input type="submit" id="continue_button" value="Continue">
			</div>
		</form>
	<?php endif; ?>
</div>
<?php include_template('installation/footer'); ?>