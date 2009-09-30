<table style="clear: both; width: 700px; margin-top: 5px;" class="padded_table" cellpadding=0 cellspacing=0>
	<tr>
		<td style="width: 200px;"><label for="url_host"><?php echo __('Server URL'); ?></label></td>
		<td style="width: auto;"><input type="text" name="url_host" id="url_host" value="<?php echo BUGSsettings::getURLhost(); ?>" style="width: 300px;"<?php if ($access_level != configurationActions::ACCESS_FULL): ?> disabled<?php endif; ?>></td>
	</tr>
	<tr>
		<td class="config_explanation" colspan="2"><?php echo __('The full url to this bug genie installation, without the trailing slash.') ?><br>(<i><?php echo __('ex: http://localhost'); ?></i>)</td>
	</tr>
	<tr>
		<td><label for="url_subdir"><?php echo __('URL subdirectory'); ?></label></td>
		<td><input type="text" onblur="$('server_path_warning_container').show();$('server_path_warning').highlight({ duration: 4});" onfocus="$('server_path_warning_container').show();$('server_path_warning').highlight({ duration: 4});" name="url_subdir" id="url_subdir" value="<?php echo BUGSsettings::getURLsubdir(); ?>" style="width: 300px;"<?php if ($access_level != configurationActions::ACCESS_FULL): ?> disabled<?php endif; ?>></td>
	</tr>
	<tr id="server_path_warning_container" style="display: none;">
		<td style="padding: 5px;" colspan="2" id="server_path_warning"><b><?php echo __('Important') ?>: </b><?php echo __('If you change this setting, you must make sure to also update the .htaccess file in the root directory to match this setting, otherwise The Bug Genie will not work correctly!'); ?></td>
	</tr>
	<tr>
		<td class="config_explanation" colspan="2"><?php echo __('The path from the server url root to the subdirectory for the bug genie, including the trailing slash.'); ?><br>(ex: <i><?php echo __('/thebuggenie/'); ?></i>)</td>
	</tr>
</table>