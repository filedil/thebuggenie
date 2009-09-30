<?php

	BUGScontext::loadLibrary('ui');

/*
	$is_registering = false;
	$does_exist = false;
	$noreg = false;
	$forgotten_user = null;
	$new_pass = null;
	$reset_password = false;

	if (BUGScontext::getRequest()->getParameter('forgot_password_username'))
	{
		$crit = new B2DBCriteria();
		$crit->addWhere(B2tUsers::UNAME, BUGScontext::getRequest()->getParameter('forgot_password_username'));
		$crit->addWhere(B2tUsers::SCOPE, BUGScontext::getScope()->getID());
		$forgotten_user = B2DB::getTable('B2tUsers')->doSelectOne($crit);
		if ($forgotten_user instanceof B2DBRow)
		{
			BUGScontext::trigger('core', 'forgotten_password', $forgotten_user);
		}
	}
	
	if (BUGScontext::getRequest()->getParameter('reset_password'))
	{
		$reset_password = true;
		$crit = new B2DBCriteria();
		$crit->addWhere(B2tUsers::UNAME, BUGScontext::getRequest()->getParameter('username'));
		$crit->addWhere(B2tUsers::PASSWD, BUGScontext::getRequest()->getParameter('key'));
		$crit->addWhere(B2tUsers::SCOPE, BUGScontext::getScope()->getID());
		$reset_pwd_user = B2DB::getTable('B2tUsers')->doSelectOne($crit);
		if ($reset_pwd_user instanceof B2DBRow)
		{
			$reset_pwd_user = BUGSfactory::userLab($reset_pwd_user->get(B2tUsers::ID));
			$new_pass = $reset_pwd_user->setRandomPassword();
			BUGScontext::trigger('core', 'password_reset', array($reset_pwd_user, $new_pass));
		}
	}
	
	if (BUGScontext::getRequest()->getParameter('desired_username'))
	{
		if (BUGSsettings::get('allowreg') == true)
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tUsers::UNAME, BUGScontext::getRequest()->getParameter('desired_username'));
			$crit->addWhere(B2tUsers::SCOPE, BUGScontext::getScope()->getID());
			if (B2DB::getTable('B2tUsers')->doCount($crit) == 0)
			{
				$is_registering = true;
			}
			else
			{
				$does_exist = true;
			}
		}
		else
		{
			$noreg = true;
		}
	}*/
	
?>
<div class="logindiv">
<table style="width: 900px;">
<tr>
<td valign="top">
<div class="loginheader"><b><?php echo __('Welcome to'); ?> '<?php echo(BUGSsettings::getTBGname()); ?>'</b></div>
<?php echo __('Please fill in your username and password below, and press "Continue" to log in.'); ?>
<?php if (BUGSsettings::get('allowreg') == true): ?> 
	<?php echo __('If you have not already registered, please use the "Register new account" link. It is completely free and takes only a minute.'); ?>
<?php else: ?>
	<?php echo __('It is not possible to register new accounts from this page. To register a new account, please contact the administrator.'); ?>
<?php endif; ?>
<br><br>
</td>
</tr>
</table>
<table cellspacing=5 style="width: 900px;">
<tr>
<td style="width: auto;" valign="top">
<form accept-charset="<?php echo BUGScontext::getI18n()->getCharset(); ?>" action="<?php echo make_url('login'); ?>" enctype="multipart/form-data" method="post" name="loginform">
<?php if (isset($login_error)): ?>
	<div class="login_error" style="margin-bottom: 5px; width: auto;">
		<?php echo $login_error; ?>
	</div>
<?php endif; ?>
<table class="b2_section_loginframe" align="center" cellpadding=0 cellspacing=0 style="width: <?php echo (BUGSsettings::get('allowreg') == true) ? 100 : 50; ?>%;">
<tr>
<td class="b2_section_loginframe_header"><?php echo __('Log in to an existing account'); ?></td>
</tr>
<tr>
<td style="padding: 5px;">
<table align="center" style="width: 100%;">
<tr>
<td class="td1" style="width: 80px; text-align: center;"><b><?php echo __('Username'); ?></b></td>
<td style="width: auto;"><input type="text" id="b2_username" name="b2_username" style="width: 200px;"></td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td class="td1" style="width: 80px; text-align: center;"><b><?php echo __('Password'); ?></b></td>
<td style="width: auto;"><input type="password" id="b2_password" name="b2_password" style="width: 200px;"></td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td style="width: auto; text-align: right;" rowspan=2><input type="submit" id="login_button" value="<?php echo __('Continue'); ?>" style="border-bottom: 0px;"></td>
<td style="width: 30px; text-align: center;" rowspan=2><a class="image" href="javascript:void(0);" onclick="javascript:document.loginform.submit();"><?php echo image_tag('forward.png'); ?></a></td>
</tr>
</table>
</td>
</tr>
</table>
</form>
</td>
<td style="width: 50%;" valign="top">
	<form accept-charset="<?php echo BUGScontext::getI18n()->getCharset(); ?>" action="login.php" enctype="multipart/form-data" method="post" name="lostpasswordform">
	<input type="hidden" name="lostpassword" value="true">
	<table class="b2_section_loginframe" align="center" cellpadding=0 cellspacing=0 style="width: 100%;">
	<tr>
	<td class="b2_section_loginframe_header"><?php echo __('Forgot password?'); ?></td>
	</tr>
	<tr>
	<td style="padding: 5px;">
		<?php if (!isset($forgotten_user) && !isset($new_pass)): ?>
			<div style="padding: 3px;">
			<?php echo __('If you have forgot your password, enter your username here, and we will send you an email that will allow you to change your username'); ?>.
			<?php if (BUGScontext::getRequest()->getParameter('forgot_password_username')): ?>
				<div style="padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #B44;"><?php echo __('This username does not exist'); ?></div>
			<?php elseif (isset($reset_password)): ?>
				<div style="padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #B44;"><?php echo __('Please use the link in the email you received'); ?></div>
			<?php endif; ?>
			</div>
			<table align="center" style="width: 100%;">
			<tr>
			<td class="td1" style="width: 120px; text-align: left;"><b><?php echo __('Username'); ?>:</b></td>
			<td style="width: 200px;"><input type="text" id="forgot_password_username" name="forgot_password_username" style="width: 90%;"></td>
			<td style="width: auto; text-align: right;"><input type="submit" id="login_button" value="<?php echo __('Send email'); ?>" style="border-bottom: 0px;"></td>
			<td style="width: 30px; text-align: center;"><a class="image" href="javascript:void(0);" onclick="javascript:document.lostpasswordform.submit();"><?php echo image_tag('forward.png'); ?></a></td>
			</tr>
			</table>
		<?php elseif ($new_pass): ?>
			<div style="padding: 3px;"><?php echo __('Your password has been reset, and an email has been sent to the email address you provided when you registered, with your new login details'); ?>.</div>
		<?php else: ?>
			<div style="padding: 3px;"><?php echo __('An email has been sent to the email address you provided when you registered, with instructions on how to reset your password'); ?>.</div>
		<?php endif; ?>
	</td>
	</tr>
	</table>
	</form>
	<br>
	<?php if (BUGSsettings::get('allowreg')): ?>
		<form accept-charset="<?php echo BUGScontext::getI18n()->getCharset(); ?>" action="login.php" enctype="multipart/form-data" method="post" name="registerform">
		<input type="hidden" name="register" value="true">
		<table class="b2_section_loginframe" align="center" cellpadding=0 cellspacing=0 style="width: 100%;">
		<tr>
		<td class="b2_section_loginframe_header"><?php echo __('Register a new account'); ?></td>
		</tr>
		<tr>
		<td style="padding: 5px;">
		<?php
		
		if (isset($is_registering))
		{
			if (BUGScontext::getRequest()->getParameter('add_user'))
			{
				$user_name = BUGScontext::getRequest()->getParameter('desired_username');
				$user_realname = BUGScontext::getRequest()->getParameter('realname');
				$user_buddyname = BUGScontext::getRequest()->getParameter('buddyname');
				$user_email = ((BUGScontext::getRequest()->getParameter('email_address') == BUGScontext::getRequest()->getParameter('email_confirm')) && BUGScontext::getRequest()->getParameter('email_address')) ? BUGScontext::getRequest()->getParameter('email_address') : "";
				$email_ok = false;
				$valid_domain = false;
				$rnd_number = false;
				$user_names = false;
				if ($user_email != "")
				{
					if ((!(stristr($user_email, "@") === false)) && (strripos($user_email, ".") > strripos($user_email, "@")))
					{
						$email_ok = true;
					}
					if ($email_ok && BUGSsettings::get('limit_registration') != '')
					{
						$allowed_domains = explode(',', BUGSsettings::get('limit_registration'));
						var_dump($allowed_domains);
						if (count($allowed_domains) > 0)
						{
							foreach ($allowed_domains as $allowed_domain)
							{
								$allowed_domain = '@' . trim($allowed_domain);
								if (strpos($user_email, $allowed_domain) !== false ) //strpos checks if $to
								{
									$valid_domain = true;
									break;
								} 							
							}
						}
						else
						{
							$valid_domain = true;
						}
					}
					else
					{
						$valid_domain = true;
					}
				}
	
				if (BUGScontext::getRequest()->getParameter('verification_no') == $_SESSION['activation_number'])
				{
					$rnd_number = true;
				}
	
				if (($user_name != "" && strlen($user_name) > 2) && ($user_buddyname != "" && strlen($user_buddyname) > 2))
				{
					$user_names = true;
				}
	
				if ($user_names && $email_ok && $rnd_number && $valid_domain)
				{
					$user_passwd = bugs_createPassword();
					$user_passwd_md5 = md5($user_passwd);
					/*$crit = new B2DBCriteria();
					$crit->addInsert(B2tUsers::UNAME, $user_name);
					$crit->addInsert(B2tUsers::PASSWD, $user_passwd_md5);
					$crit->addInsert(B2tUsers::BUDDYNAME, $user_buddyname);
					$crit->addInsert(B2tUsers::REALNAME, $user_realname);
					$crit->addInsert(B2tUsers::EMAIL, $user_email);
					$crit->addInsert(B2tUsers::GROUP_ID, BUGSsettings::get('defaultgroup'));
					$crit->addInsert(B2tUsers::STATE, BUGSsettings::get('offlinestate'));
					$crit->addInsert(B2tUsers::ENABLED, 1);
					$crit->addInsert(B2tUsers::JOINED, $_SERVER["REQUEST_TIME"]);
					$crit->addInsert(B2tUsers::PRIVATE_EMAIL, 1);
					$crit->addInsert(B2tUsers::SCOPE, BUGScontext::getScope()->getID());
					
					$resultset = B2DB::getTable('B2tUsers')->doInsert($crit);*/
					$user = BUGSuser::createNew($user_name, $user_realname, $user_buddyname, BUGScontext::getScope()->getID(), false, true, $user_passwd, $user_email);
					//$uid = $resultset->getInsertID();
					$user->setGroup(BUGSsettings::get('defaultgroup'));
					
					BUGScontext::trigger("core", 'user_registration', array($user, $user_passwd));
					$hasregistered = true;
				}
			}
			else
			{
				$email_ok = true;
				$rnd_number = true;
				$user_names = true;
				$valid_domain = true;
			}
	
			?>
			<input type="hidden" name="desired_username" value="<?php print BUGScontext::getRequest()->getParameter('desired_username'); ?>">
			<input type="hidden" name="add_user" value="true">
			<?php
	
				if ($hasregistered)
				{
					?>
					<b><?php echo __('Thank you for registering!Thank you for registering!'); ?></b><br><?php echo __('The account has now been registered - check your email inbox for the activation email. Please be patient - this email can take up to two hours to arrive.'); ?>
					<table align="center" style="width: 100%;">
					<tr>
					<td>
					<?php
				}
				else
				{
						if (!($user_names && $email_ok && $rnd_number && $valid_domain))
						{
							?><div style="color: #C33; padding: 3px; border: 1px solid #DDD; margin-bottom: 5px;"><b><?php echo __('You need to fill out all fields correctly.'); ?></b><?php
	
								if (!$user_names)
								{
									echo '<br>*&nbsp;' . __('Remember to fill out the "Buddy name" field.');
								}
								if (!$email_ok)
								{
									echo '<br>*&nbsp;' . __('The email address must be valid, and must be typed twice.');
								}
								if (!$valid_domain)
								{
									echo '<br>*&nbsp;' . __('Email adresses from this domain can not be used.');
								}
								if (!$rnd_number)
								{
									echo '<br>*&nbsp;' . __('To prevent automatic sign-ups, enter the verification number shown below.');
								}
	
							?></div><?php
						}
	
					?>
					<table align="center" style="width: 100%;">
					<tr>
					<td style="width: 120px; text-align: left;"><b><?php echo __('Desired username'); ?>:</b></td>
					<td style="width: auto;"><?php print BUGScontext::getRequest()->getParameter('desired_username'); ?></td>
					<td style="width: 30px;"><a href="login.php" style="font-size: 9px;">Change</a></td>
					</tr>
					<tr>
					<td colspan=3 style="padding: 5px; padding-left: 0px;"><?php echo __('The username you requested is available. To register it, please fill out the information below.'); ?> <i>(<?php echo __('Required information is marked with an asterisk'); ?>: <b>*</b>)</i></td>
					</tr>
					<tr>
					<td style="text-align: left;"><b>*&nbsp;<?php echo __('Buddy name'); ?>:</b></td>
					<td style="width: auto;" colspan=2><input type="text" id="buddyname" name="buddyname" value="<?php print BUGScontext::getRequest()->getParameter('buddyname'); ?>" style="width: 90%;<?php print (!$user_names) ? " background-color: #FBB;" : ""; ?>"></td>
					</tr>
					<tr>
					<td style="text-align: left;">&nbsp;&nbsp;&nbsp;<?php echo __('Real name'); ?>:</td>
					<td style="width: auto;" colspan=2><input type="text" id="realname" name="realname" value="<?php print BUGScontext::getRequest()->getParameter('realname'); ?>" style="width: 90%;"></td>
					</tr>
					<tr>
					<td style="text-align: left;"><b>*&nbsp;<?php echo __('E-mail address'); ?>:</b></td>
					<td style="width: auto;" colspan=2><input type="text" id="email_address" name="email_address" value="<?php print BUGScontext::getRequest()->getParameter('email_address'); ?>" style="width: 90%;<?php print (!$email_ok) ? " background-color: #FBB;" : ""; ?>"></td>
					</tr>
					<tr>
					<td style="text-align: left;"><b>*&nbsp;<?php echo __('Confirm e-mail'); ?>:</b></td>
					<td style="width: auto;" colspan=2><input type="text" id="email_confirm" name="email_confirm" value="<?php print BUGScontext::getRequest()->getParameter('email_confirm'); ?>" style="width: 90%;<?php print (!$email_ok) ? " background-color: #FBB;" : ""; ?>"></td>
					</tr>
					<tr id="continue_button_tr"<?php print (!$rnd_number) ? " style=\"display: none;\"" : ""; ?>>
					<td>&nbsp;</td>
					<td style="width: auto; text-align: right;"><input type="button" id="login_button" value="<?php echo __('Continue'); ?>" style="border-bottom: 0px;" onclick="javascript:showHide('continue_button_tr');showHide('register_button_tr');"></td>
					<td style="text-align: center;"><a class="image" href="javascript:void(0);" onclick="javascript:showHide('register_button_tr');showHide('continue_button_tr');"><?php echo image_tag('forward.png'); ?></a></td>
					</tr>
					<tr id="register_button_tr"<?php print ($rnd_number) ? " style=\"display: none;\"" : ""; ?>>
					<td colspan=3>
					<table align="center" style="margin-top: 10px; width: 100%;">
					<tr>
					<td style="width: 120px;"><?php echo __('Enter this number'); ?>:</td>
					<td style="width: auto;"><?php
	
						$_SESSION['activation_number'] = bugs_printRandomNumber();
	
					?></td>
					<td style="width: 30px;">&nbsp;</td>
					</tr>
					<tr>
					<td style="text-align: left;"><b>*&nbsp;<?php echo __('%enter_number% in this box', array('%enter_number%' => '')); ?>:</b></td>
					<td style="width: auto;" colspan=2><input type="text" onfocus="aB = document.getElementById('register_button'); aB.disabled = false;" id="verification_no" name="verification_no" style="width: 100px;<?php print (!$rnd_number) ? " background-color: #FBB;" : ""; ?>"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td style="text-align: right;"><input type="submit" id="register_button" value="<?php echo __('Register'); ?>" style="border-bottom: 0px;" disabled></td>
					<td style="text-align: center;"><a class="image" href="javascript:void(0);" onclick="javascript:document.registerform.submit();"><?php echo image_tag('forward.png'); ?></a></td>
					</tr>
					</table>
					<?php
				}
			?>
			</td>
			</tr>
			</table>
			<?php
		}
		elseif (BUGScontext::getRequest()->getParameter('verify_user'))
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tUsers::UNAME, BUGScontext::getRequest()->getParameter('uname'));
			$crit->addWhere(B2tUsers::SCOPE, BUGScontext::getScope()->getID());
			$row = B2DB::getTable('B2tUsers')->doSelectOne($crit);
			if (BUGScontext::getRequest()->getParameter('verification_code') == $row->get(B2tUsers::PASSWD))
			{
				$crit = new B2DBCriteria();
				$crit->addUpdate(B2tUsers::ACTIVATED, 1);
				$crit->addUpdate(B2tUsers::ENABLED, 1);
				B2DB::getTable('B2tUsers')->doUpdateById($crit, $row->get(B2tUsers::ID));
				?><b><?php echo __('Thank you!'); ?></b><br><?php echo __('Your account has now been activated. Please log in by entering your username and password in the fields to the left.');
			}
			else
			{
				?><b><?php echo __('There seems to be something wrong with your verification code.'); ?></b><br><?php echo __('Please copy and paste the link from the activation email into your browser address bar, and try again.');
			}
		}
		else
		{
			?>
			<table align="center" style="width: 100%;">
			<tr>
			<td class="td1" style="width: 120px; text-align: left;"><b><?php echo __('Desired username'); ?>:</b></td>
			<td style="width: 200px;"><input type="text" id="desired_username" name="desired_username" style="width: 90%;"></td>
			<td style="width: auto; text-align: right;"><input type="submit" id="login_button" value="<?php echo __('Check'); ?>" style="border-bottom: 0px;"></td>
			<td style="width: 30px; text-align: center;"><a class="image" href="javascript:void(0);" onclick="javascript:document.registerform.submit();"><?php echo image_tag('forward.png'); ?></a></td>
			</tr>
			</table>
			<?php
		}
		
		?>
		</td>
		</tr>
		</table>
		</form>
		<?php
		
		if (isset($does_exist))
		{
			?>
			<div class="login_error" style="width: auto; margin-top: 5px;"><?php echo __('The desired username is not available. Please try again.'); ?></div>
			<?php
		}
	
		?>
	<?php endif; ?>
</td>
</tr>
</table>
