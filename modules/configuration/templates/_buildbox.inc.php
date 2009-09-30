<?php BUGScontext::loadLibrary('ui'); ?>
<?php $b_id = $aBuild->getID(); ?>
<table cellpadding=0 cellspacing=0 style="width: 100%;">
<tr id="show_build_<?php print $b_id; ?>" class="canhover_light">
	<td style="width: 20px; padding: 7px 2px 0 2px; text-align: center; vertical-align: top;"><?php echo image_tag('icon_' . (($aBuild->isReleased()) ? 'release' : 'build') . '.png'); ?></td>
	<td style="width: auto; padding: 2px 0 2px 0;">
		<div style="padding-left: 2px;">
			<b><?php print $aBuild->getName(); ?></b><br>
		<?php if ($aBuild->isReleased()): ?>
			<?php echo __('Released %release_date%', array('%release_date%' => bugs_formatTime($aBuild->getReleaseDate(), 5))); ?>
		<?php else: ?>
			<span class="faded_medium"><?php echo __('Not released yet'); ?></span>
		<?php endif; ?>
		</div>
	</td>
<?php if ($access_level == configurationActions::ACCESS_FULL): ?>
	<td style="width: 250px; text-align: right;">
		<div style="float: right;">
		<?php if (!$aBuild->isDefault()): ?>
			<a href="javascript:void(0);" onclick="doBuildAction('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'markdefault')); ?>', <?php print $b_id; ?>, 'markdefault', 'all');" class="image"><?php echo image_tag('icon_build_default.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Set this build / release as the initial default when reporting new issues') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php endif; ?>
			<a href="javascript:void(0);" onclick="$('show_build_<?php print $b_id; ?>').addClassName('selected_green');$('show_build_<?php print $b_id; ?>').removeClassName('canhover_light');$('addtoopen_build_<?php print $b_id; ?>').show();$('build_<?php print $b_id; ?>_info').hide();" class="image"><?php echo image_tag('icon_build_addtoopen.png' , array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Add this build / release to the list of affected builds / releases for all open issues') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php if (!$aBuild->isReleased()): ?>
			<a href="javascript:void(0);" onclick="doBuildAction('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'release')); ?>', <?php print $b_id; ?>, 'release', 'one');" class="image"><?php echo image_tag('icon_release.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __("Mark this build / release as &laquo;Released&raquo;") . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php else: ?>
			<a href="javascript:void(0);" onclick="doBuildAction('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'retract')); ?>', <?php print $b_id; ?>, 'retract', 'one');" class="image"><?php echo image_tag('icon_retract.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __("Mark this build / release as &laquo;Not released&raquo;") . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php endif; ?>
		<?php if ($aBuild->isLocked()): ?>
			<a href="javascript:void(0);" onclick="doBuildAction('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'unlock')); ?>', <?php print $b_id; ?>, 'unlock', 'one');" class="image"><?php echo image_tag('icon_locked.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Allow users to report issues for this build / release') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php else: ?>
			<a href="javascript:void(0);" onclick="doBuildAction('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'lock')); ?>', <?php print $b_id; ?>, 'lock', 'one');" class="image"><?php echo image_tag('icon_unlocked.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Do not allow users to report issues for this build / release') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		<?php endif; ?>
			<a href="javascript:void(0);" onclick="$('edit_build_<?php print $b_id; ?>').show();$('show_build_<?php print $b_id; ?>').hide();" class="image"><?php echo image_tag('icon_edit.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Edit information about this build') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
			<a href="javascript:void(0);" onclick="$('show_build_<?php print $aBuild->getID(); ?>').addClassName('selected_red');$('show_build_<?php print $aBuild->getID(); ?>').removeClassName('canhover_light');$('del_build_<?php print $b_id; ?>').show();$('build_<?php print $b_id; ?>_info').hide();" class="image"><?php echo image_tag('action_cancel_small.png', array('onmouseover' => '$(\'build_' . $b_id . '_info\').update(\'' . __('Delete this build permanently (confirmation needed)') . '\')', 'onmouseout' => '$(\'build_' . $b_id . '_info\').update(\'&nbsp;\')')); ?></a>
		</div>
		<div style="float: right; margin-right: 10px; font-weight: bold;"><?php echo __('Actions'); ?>:</div>
	</td>
<?php endif; ?>
</tr>
<?php if ($access_level == configurationActions::ACCESS_FULL): ?>
<tr id="edit_build_<?php print $b_id; ?>" class="selected_green" style="display: none;">
	<td style="width: 20px; padding: 2px; padding-top: 10px;" valign="top"><?php echo image_tag('icon_edit_build.png'); ?></td>
	<td style="width: auto; padding: 2px;" colspan="2">
		<form accept-charset="<?php echo BUGScontext::getI18n()->getCharset(); ?>" action="<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'update')); ?>" method="post" id="edit_build_<?php print $b_id; ?>_form" onsubmit="updateBuild('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'update')); ?>', <?php echo $b_id; ?>);return false;">
			<table cellpadding=0 cellspacing=0 style="width: 100%;">
				<tr>
					<td style="width: 120px;"><label for="build_name_<?php echo $b_id; ?>"><?php echo __('Build / release name'); ?>:</label></td>
					<td style="width: auto;"><input type="text" name="build_name" name="build_name_<?php echo $b_id; ?>" style="width: 300px;" value="<?php print $aBuild->getName(); ?>"></td>
					<td style="width: 100px; text-align: right;"><label for="ver_mj_<?php echo $b_id; ?>"><?php echo __('Ver: %version_number%', array('%version_number%' => '')); ?></label></td>
					<td style="width: 100px; text-align: right;"><input type="text" name="ver_mj" id="ver_mj_<?php echo $b_id; ?>" style="width: 25px; text-align: center;" value="<?php print $aBuild->getMajor(); ?>">&nbsp;.&nbsp;<input type="text" name="ver_mn" style="width: 25px; text-align: center;" value="<?php print $aBuild->getMinor(); ?>">&nbsp;.&nbsp;<input type="text" name="ver_rev" style="width: 25px; text-align: center;" value="<?php print $aBuild->getRevision(); ?>"></td>
				</tr>
				<tr>
				<td><label for="release_month_<?echo $b_id; ?>"><?php echo __('Release date'); ?>:</label></td>
				<td style="text-align: left;">
					<select style="width: 85px;" name="release_month" id="release_month_<?php print $b_id; ?>"<?php if (!$aBuild->isReleased()): ?> disabled<?php endif; ?>>
					<?php for($cc = 1;$cc <= 12;$cc++): ?>
						<option value=<?php print $cc; ?><?php echo ($aBuild->getReleaseDateMonth() == $cc) ? " selected" : "" ?>><?php echo bugs_formatTime(mktime(0, 0, 0, $cc, 1), 15); ?></option>
					<?php endfor; ?>
					</select>
					<select style="width: 40px;" name="release_day" id="release_day_<?php print $b_id; ?>"<?php if (!$aBuild->isReleased()): ?> disabled<?php endif; ?>>
					<?php for($cc = 1;$cc <= 31;$cc++): ?>
						<option value=<?php print $cc; ?><?php echo ($aBuild->getReleaseDateDay() == $cc) ? " selected" : "" ?>><?php echo $cc; ?></option>
					<?php endfor; ?>
					</select>
					<select style="width: 55px;" name="release_year" id="release_year_<?php print $b_id; ?>"<?php if (!$aBuild->isReleased()): ?> disabled<?php endif; ?>>
					<?php for($cc = 2000;$cc <= (date("Y") + 5);$cc++): ?>
						<option value=<?php print $cc; ?><?php echo ($aBuild->getReleaseDateYear() == $cc) ? " selected" : "" ?>><?php echo $cc; ?></option>
					<?php endfor; ?>
					</select>
				</td>
				<td colspan="2" style="padding-top: 2px; text-align: right;">
					<a href="javascript:void(0);" onclick="$('edit_build_<?php print $b_id; ?>').hide();$('show_build_<?php print $b_id; ?>').show();" style="font-size: 12px;"><?php echo __('Cancel'); ?></a>
					&nbsp;&nbsp;<?php echo __('%cancel% or %save%', array('%save%' => '', '%cancel%' => '')); ?>&nbsp;&nbsp;
					<input type="submit" value="<?php echo __('Save changes'); ?>">
				</td>
				</tr>
			</table>
		</form>
	</td>
</tr>
<tr>
	<td colspan="3" style="text-align: right; height: 30px; padding: 3px; font-size: 12px; color: #AAA;" id="build_<?php echo $b_id; ?>_info">&nbsp;</td>
	<td colspan="3" style="text-align: right; height: 30px; padding: 3px; display: none; font-size: 12px; color: #AAA;" id="build_<?php echo $b_id; ?>_indicator">
		<span style="float: right;"><?php echo __('Please wait'); ?>...</span>
		<?php echo image_tag('spinning_20.gif', array('style' => 'float: right; margin-right: 5px;')); ?>
	</td>
</tr>
<tr id="addtoopen_build_<?php print $b_id; ?>" style="display: none; background-color: #F5F5F5;">
	<td colspan=3 style="border-top: 1px solid #DDD; border-bottom: 1px solid #DDD; background-color: #F1F1F1; padding: 5px;">
		<strong><?php echo __('Please specify and confirm'); ?></strong><br>
		<?php echo __('You can specify a selection of issues to be updated, from the choices below'); ?>.
		<?php if ($aBuild->isProjectBuild()): ?>
			<?php echo __('This build / release will then be added to the list of affected builds / releases on all open issues for this project'); ?>
		<?php else: ?>
			<?php echo __('This build / release will then be added to the list of affected builds / releases on all open issues for this edition'); ?>
		<?php endif; ?>.
		<form accept-charset="<?php echo BUGScontext::getI18n()->getCharset(); ?>" action="<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'addtoopen')); ?>" method="post" id="add_to_open_build_<?php print $b_id; ?>_form" onsubmit="addToOpenBuild('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'addtoopen')); ?>', <?php echo $b_id; ?>);return false;">
			<table cellpadding=0 cellspacing=0 style="width: 100%;" class="padded_table">
				<tr>
					<td style="width: auto; margin-right: 10px; text-align: right;"><label for="build_<?php echo $b_id; ?>_status"><?php echo __('Status'); ?></label></td>
					<td style="width: auto;">
						<select name="status" id="build_<?php echo $b_id; ?>_status">
							<option value="" selected><?php echo __('All statuses'); ?></option>
							<?php foreach (BUGSdatatype::getStatusTypes() as $aStatus): ?>
								<option style="color: <?php echo $aStatus->getItemdata(); ?>" value=<?php echo $aStatus->getID(); ?>><?php echo $aStatus->getName(); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td style="width: auto; margin-right: 10px; text-align: right;"><label for="build_<?php echo $b_id; ?>_category"><?php echo __('Category'); ?></label></td>
					<td style="width: auto;">
						<select name="category" id="build_<?php echo $b_id; ?>_category">
							<option value="" selected><?php echo __('All categories'); ?></option>
							<?php foreach (BUGSdatatype::getCategories() as $aCategory): ?>
								<option value=<?php echo $aCategory->getID(); ?>><?php echo $aCategory->getName(); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td style="width: auto; margin-right: 10px; text-align: right;"><label for="build_<?php echo $b_id; ?>_issuetype"><?php echo __('Issue type'); ?></label></td>
					<td style="width: auto;">
						<select name="issuetype" id="build_<?php echo $b_id; ?>_issuetype">
							<option value="" selected><?php echo __('All issue types'); ?></option>
							<?php foreach (BUGSissuetype::getAll($aBuild->getProject()->getID()) as $anIssuetype): ?>
								<option value=<?php echo $anIssuetype->getID(); ?>><?php echo $anIssuetype->getName(); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>
			<div style="text-align: right; padding: 3px;">
				<a href="javascript:void(0);" onclick="$('show_build_<?php print $b_id; ?>').removeClassName('selected_green');$('show_build_<?php print $b_id; ?>').addClassName('canhover_light');$('addtoopen_build_<?php print $b_id; ?>').hide();$('build_<?php print $b_id; ?>_info').show();" style="font-size: 12px;"><?php echo __('Cancel'); ?></a>
				&nbsp;&nbsp;<?php echo __('%cancel% or %save%', array('%save%' => '', '%cancel%' => '')); ?>&nbsp;&nbsp;
				<input type="submit" value="<?php echo __('Add to open issues'); ?>">
			</div>
		</form>
	</td>
</tr>
<tr id="del_build_<?php print $b_id; ?>" class="selected_red" style="display: none;">
	<td colspan=3 style="border-bottom: 1px solid #E55; padding: 5px; height: 33px; font-size: 12px;">
		<div style="float: right;">
			<a href="javascript:void(0);" onclick="$('show_build_<?php print $aBuild->getID(); ?>').removeClassName('selected_red');$('show_build_<?php print $aBuild->getID(); ?>').addClassName('canhover_light');$('del_build_<?php print $b_id; ?>').hide();$('build_<?php print $b_id; ?>_info').show();"><b><?php echo __('Cancel'); ?></b></a>
			&nbsp;<?php echo __('%cancel% or %delete%', array('%delete%' => '', '%cancel%' => '')); ?>&nbsp;
			<button onclick="deleteBuild('<?php echo make_url('configure_build_action', array('build_id' => $b_id, 'build_action' => 'delete')); ?>', <?php print $b_id; ?>);" style="font-size: 11px;"><?php echo __('Delete it'); ?></button>
		</div>
		<span style="padding-top: 2px; float: left;"><?php echo __('Please confirm that you really want to delete this build / release'); ?></span>
	</td>
</tr>
<?php endif; ?>
</table>