<?php

	class BUGSsvnintegration extends BUGSmodule 
	{
		
		public function __construct($m_id, $res = null)
		{
			parent::__construct($m_id, $res);
			$this->_module_menu_title = BUGScontext::getI18n()->__("SVN integration");
			$this->_module_config_title = BUGScontext::getI18n()->__("SVN integration");
			$this->_module_config_description = BUGScontext::getI18n()->__('Configure source code integration from this section.');
			$this->_module_version = "1.0";
			if ($this->_enabled)
			{
				//B2DB::loadNewTable(new B2tSVNintegration());
				$this->addAvailableSection('core', 'viewissue_right_middle', 'List of updated files for an issue');
				$this->addAvailablePermission('core', 'viewissue_right_middle', 'List of updated files when viewing an issue');
				$this->addAvailablePermission('core', 'viewproject_right_top', '"View code" link in project overview');
			}
		}
		
		static public function install($scope = null)
		{
  			if ($scope === null)
  			{
  				$scope = BUGScontext::getScope()->getID();
  			}
			$module = parent::_install('svn_integration', 
  									  'SVN integration', 
  									  'Enables integration with SVN',
  									  'BUGSsvnintegration',
  									  true, false, false,
  									  '1.0',
  									  true,
  									  $scope);

			$module->setPermission(0, 0, 0, true, $scope);
			$module->enableSection('core', 'viewissue_right_middle', $scope);
			$module->enableSection('core', 'viewproject_right_top', $scope);

			if ($scope == BUGScontext::getScope()->getID())
			{
				B2DB::loadNewTable(new B2tSVNintegration());
				B2DB::getTable('B2tSVNintegration')->create();
			}
			
			try
			{
				self::loadFixtures($scope);
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		
		static function loadFixtures($scope)
		{
		}
					
		public function uninstall($scope)
		{
			B2DB::getTable('B2tSVNintegration')->drop();
			parent::uninstall($scope);
		}
		
		public function getCommentAccess($target_type, $target_id, $type = 'view')
		{
			
		}
		
		public function loadHelpTitle($topic)
		{
			switch ($topic)
			{
				case 'main':
					return BUGScontext::getI18n()->__('Main');
					break;
				case 'howto':
					return BUGScontext::getI18n()->__('How to set up SVN integration');
					break;
			}
			return parent::loadHelpTitle($topic);
		}
		
		public function enableSection($module, $identifier, $scope)
		{
			$function_name = '';
			switch ($module . '_' . $identifier)
			{
				case 'core_viewissue_right_middle':
					$function_name = 'section_viewissueRightMiddle';
					break;
				case 'viewproject_right_top':
					$function_name = 'section_viewProject_viewCode';
					break;
			}
			if ($function_name != '') parent::createSection($module, $identifier, $function_name, $scope);
		}
		
		public function section_viewissueRightMiddle($theIssue)
		{
			?>
			<div style="margin-top: 10px; margin-bottom: 0px; border: 0px;">
				<div style="border-bottom: 1px solid #DDD; padding: 3px; font-size: 12px;">
				<b><?php echo BUGScontext::getI18n()->__('Subversion checkins'); ?></b>
				</div>
				<div style="padding-top: 5px; padding-bottom: 5px;" id="svn_checkins">
				<?php
				
					$crit = new B2DBCriteria();
					$crit->addWhere(B2tSVNintegration::ISSUE_NO, $theIssue->getID());
					$crit->addOrderBy(B2tSVNintegration::DATE, 'desc');
					if ($results = B2DB::getTable('B2tSVNintegration')->doSelect($crit))
					{
						$vvcpath_setting = 'viewvc_path_' . $theIssue->getProject()->getID();
						$viewvc_path = BUGScontext::getModule('svn_integration')->getSetting($vvcpath_setting);
						
						echo '<table cellpadding=0 cellspacing=0 style="width: 100%;">';
						while ($results->next())
						{
							$theUser = BUGSfactory::userLab($results->get(B2tSVNintegration::AUTHOR));
							echo '<tr>';
							echo '<td class="issuedetailscontentsleft" style="border-bottom: 0px; padding-right: 20px;">';
							echo '<span style="font-size: 10px;">[rev <b>'.$results->get(B2tSVNintegration::NEW_REV).'</b>] </span>';
							if ($viewvc_path)
							{
								echo '<a href="' . $viewvc_path . $results->get(B2tSVNintegration::FILE_NAME) . '?view=log" target="_blank"><b>' . $results->get(B2tSVNintegration::FILE_NAME) . '</b></a>';
							}
							else
							{
								echo $results->get(B2tSVNintegration::FILE_NAME);
							}
							echo '</td>';
							if ($viewvc_path)
							{
								echo '<td class="issuedetailscontentscenter" style="border-bottom: 0px; padding-right: 10px;"><a href="' . $viewvc_path . $results->get(B2tSVNintegration::FILE_NAME) . '?r1=' . $results->get(B2tSVNintegration::OLD_REV) . '&amp;r2=' . $results->get(B2tSVNintegration::NEW_REV) . '" target="_blank""><b>' . BUGScontext::getI18n()->__('Diff') . '</b></a></td>';
								echo '<td class="issuedetailscontentscenter" style="border-bottom: 0px; padding-right: 10px;"><a href="' . $viewvc_path . $results->get(B2tSVNintegration::FILE_NAME) . '?revision=' . $results->get(B2tSVNintegration::NEW_REV) . '&amp;view=markup" target="_blank""><b>' . BUGScontext::getI18n()->__('View') . '</b></a></td>';
							}
							echo '</tr>';
						}
					}
					else
					{
						echo '<tr><td style="border-bottom: 0px; padding-left: 5px; color: #AAA;">' . BUGScontext::getI18n()->__('There are no SVN checkins for this issue') . '</td></tr>';
					}
					echo '</table>';
				
				?>
				</div>
			</div>
			<?php
		}
		
		public function section_viewProject_viewCode($theProject)
		{
			$vvcpath_setting = 'viewvc_path_' . $theProject->getID();
			$viewvc_path = BUGScontext::getModule('svn_integration')->getSetting($vvcpath_setting);
			if ($viewvc_path != '')
			{
				echo '<tr>';
				echo '<td class="imgtd" style="padding: 2px;">' . image_tag('svn_integration/icon_view_code.png') . '</td>';
				echo '<td style="padding: 2px;">';
				echo '<a href="' . $viewvc_path . '" target="_blank"><b>'. BUGScontext::getI18n()->__('View code') . '</b></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		
		public function invokeSection($page, $identifier, $vars = array())
		{
			$section = $page . '_' . $identifier;
			switch ($section)
			{
				case 'viewissue_right_middle':
					$theIssue = $vars;
					break;
				case 'help_svn_integration_howto':
					break;
				case 'help_svn_integration_main':
					break;
			}
		}
		
		public function getAvailableCommandLineCommands()
		{
			$retarr = array();
			$retarr[] = array('command' => 'svnupdate', 'params' => 'issue_no filename old_rev new_rev', 'description' => "Updates an issue with information about a sourcecode change", 'help' => 'This command can be run by a post-commit svn-hook to update an issue with information about a change in a sourcefile.', 'function' => 'svnUpdate');
			
			return $retarr;
		}
		
		public function svnUpdate($argv)
		{
			if (count($argv) == 5)
			{
				$issue_uniqueid = BUGSissue::getIssueIDfromLink($argv[2]); 
				
				if ($issue_uniqueid != 0)
				{
					$crit = new B2DBCriteria();
					$crit->addInsert(B2tSVNintegration::ISSUE_NO, $issue_uniqueid); 
					$crit->addInsert(B2tSVNintegration::FILE_NAME, $argv[3]); 
					$crit->addInsert(B2tSVNintegration::NEW_REV, $argv[5]);
					$crit->addInsert(B2tSVNintegration::DATE, $_SERVER["REQUEST_TIME"]);
					$crit->addInsert(B2tSVNintegration::SCOPE, BUGScontext::getScope());
					B2DB::getTable('B2tSVNintegration')->doInsert($crit);
					return true;
				}
				else
				{
					echo 'I can\'t update this issue - you have to provide more information.' . "\n";
					echo 'Type ' . $argv[0] . formatText(' explain', 'green', 'bold') . formatText(' svn_integration:svnupdate', 'magenta') . " for more information.\n";
					return false;
				}
			}
			else
			{
				echo 'I can\'t update this issue - you have to provide more information.' . "\n";
				echo 'Type ' . $argv[0] . formatText(' explain', 'green', 'bold') . formatText(' svn_integration:svnupdate', 'magenta') . " for more information.\n";
				return false;
			}
		}
		
	}

?>
