<table style="clear: both; width: 700px; margin-top: 5px;" class="padded_table" cellpadding=0 cellspacing=0>
	<tr>
		<td style="width: 200px;"><label for="language"><?php echo __('Interface language'); ?></label></td>
		<td style="width: auto;">
			<select name="language" id="language" style="width: 300px;"<?php if ($access_level != configurationActions::ACCESS_FULL): ?> disabled<?php endif; ?>>
			<?php foreach ($languages as $lang_code => $lang_desc): ?>
				<option value="<?php echo $lang_code; ?>" <?php if (BUGSsettings::getLanguage() == $lang_code): ?> selected<?php endif; ?>><?php echo $lang_desc; ?></option>
			<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="config_explanation" colspan="2"><?php echo __('This is the language that will be used in The Bug Genie. Depending on other settings, users may change the language displayed to them.'); ?></td>
	</tr>
	<tr>
		<td><label for="charset"><?php echo __('Charset'); ?></label></td>
		<td><input type="text" name="charset" id="charset" value="<?php echo BUGSsettings::getCharset(); ?>" style="width: 150px;"<?php if ($access_level != configurationActions::ACCESS_FULL): ?> disabled<?php endif; ?>></td>
	</tr>
	<tr>
		<td class="config_explanation" colspan="2"><?php echo __('What charset to use for the selected language - leave blank to use the charset specified in the language file (currently %charset%)', array('%charset%' => '<b>' . BUGScontext::getI18n()->getLangCharset() . '</b>')); ?></td>
	</tr>
	<tr>
		<td><label for="server_timezone"><?php echo __('Server timezone'); ?></label></td>
		<td>
			<select name="server_timezone" id="server_timezone" style="width: 150px;"<?php if ($access_level != configurationActions::ACCESS_FULL): ?> disabled<?php endif; ?>>
				<?php for ($cc = 12;$cc >= 1;$cc--): ?>
					<option value="-<?php echo $cc; ?>"<?php if (BUGSsettings::getGMToffset() == -$cc): ?> selected<?php endif; ?>>GMT -<?php echo $cc; ?></option>
				<?php endfor; ?>
				<option value="0"<?php if (BUGSsettings::getGMToffset() == 0): ?> selected<?php endif; ?>>GMT/UTC</option>
				<?php for ($cc = 1;$cc <= 12;$cc++): ?>
					<option value="<?php echo $cc; ?>"<?php if (BUGSsettings::getGMToffset() == $cc): ?> selected<?php endif; ?>>GMT +<?php echo $cc; ?></option>
				<?php endfor; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="config_explanation" colspan="2"><?php echo __('The timezone used for The Bug Genie'); ?></td>
	</tr>
</table>