<?php

	/**
	 * Module class, extended by all thebuggenie modules
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage core
	 */

	/**
	 * Module class, extended by all thebuggenie modules
	 *
	 * @package thebuggenie
	 * @subpackage core
	 */
	abstract class BUGSmodule extends BUGSidentifiableclass implements BUGSidentifiable 
	{
		
		protected $_classname = '';
		protected $_description = '';
		protected $_enabled = false;
		protected $_longname = '';
		protected $_moduletype = 0;
		protected $_showinconfig = false;
		protected $_showinmenu = false;
		protected $_showinusermenu = false;
		protected $_version = '';
		protected $_shortname = '';
		protected $_module_menu_title = '';
		protected $_module_config_title = '';
		protected $_module_config_description = '';
		protected $_module_version = '';
		protected $_enabledsections = array();
		protected $_availablepermissions = array();
		protected $_availablesections = array();
		protected $_settings = array();
		
		static protected $_permissions = array();
		
		/**
		 * Class constructor
		 *
		 * @param integer $m_id
		 * @param B2DBRow $row
		 */
		public function __construct($m_id, $row = null)
		{
			if ($row == null)
			{
				$row = B2DB::getTable('B2tModules')->doSelectById($m_id);
			}
			$this->_itemid = $m_id;
			$this->_name = $row->get(B2tModules::MODULE_NAME);
			$this->_classname = $row->get(B2tModules::CLASSNAME);
			$this->_description = $row->get(B2tModules::DESC);
			$this->_enabled = ($row->get(B2tModules::ENABLED) == 1) ? true : false;
			$this->_longname = $row->get(B2tModules::MODULE_LONGNAME);
			$this->_moduletype = $row->get(B2tModules::MODULE_TYPE);
			$this->_shortname = $row->get(B2tModules::MODULE_NAME);
			$this->_showinconfig = ($row->get(B2tModules::SHOW_IN_CONFIG) == 1) ? true : false;
			$this->_showinmenu = ($row->get(B2tModules::SHOW_IN_MENU) == 1) ? true : false;
			$this->_showinusermenu = ($row->get(B2tModules::SHOW_IN_USERMENU) == 1) ? true : false;
			$this->_version = $row->get(B2tModules::VERSION);
		}
		
		public function log($message)
		{
			BUGSlogging::log($message, $this->getName());
		}
		
		public function disable()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::ENABLED, 0);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_enabled = false;
		}

		public function enable()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::ENABLED, 1);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_enabled = true;
		}
		
		public function showInMenu()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::SHOW_IN_MENU, 1);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_showinmenu = true;
		}
		
		public function hideFromMenu()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::SHOW_IN_MENU, 0);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_showinmenu = false;
		}
		
		public function showInUserMenu()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::SHOW_IN_USERMENU, 1);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_showinusermenu = true;
		}
		
		public function hideFromUserMenu()
		{
			$crit = new B2DBCriteria();
			$crit->addUpdate(B2tModules::SHOW_IN_USERMENU, 0);
			B2DB::getTable('B2tModules')->doUpdateById($crit, $this->getID());
			$this->_showinusermenu = false;
		}
		
		abstract public function getCommentAccess($target_type, $target_id, $type = 'view');
		
		protected function uninstall($scope)
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModules::MODULE_NAME, $this->_name);
			$crit->addWhere(B2tModules::SCOPE, $scope);
			$res = B2DB::getTable('B2tModules')->doDelete($crit);
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModuleSections::MODULE_NAME, $this->_name);
			$crit->addWhere(B2tModuleSections::SCOPE, $scope);
			$res = B2DB::getTable('B2tModuleSections')->doDelete($crit);
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tSettings::MODULE, $this->_name);
			$crit->addWhere(B2tSettings::SCOPE, $scope);
			$res = B2DB::getTable('B2tSettings')->doDelete($crit);
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tPermissions::MODULE, $this->_name);
			$crit->addWhere(B2tPermissions::SCOPE, $scope);
			$res = B2DB::getTable('B2tPermissions')->doDelete($crit);
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tComments::MODULE, $this->_name);
			$crit->addWhere(B2tComments::SCOPE, $scope);
			$res = B2DB::getTable('B2tComments')->doDelete($crit);
		}
		
		/**
		 * Installs the module, adds it to the list of loaded modules and returns it
		 *
		 * @param string $identifier
		 * @param string $longname
		 * @param string $description
		 * @param string $classname
		 * @param bool   $show_in_config
		 * @param bool   $show_in_menu
		 * @param bool   $show_in_usermenu
		 * @param string $version
		 * @param bool   $enabled
		 * @param int	$scope
		 * 
		 * @return BUGSmodule
		 */
		static protected function _install($identifier, $longname, $description, $classname, $show_in_config, $show_in_menu, $show_in_usermenu, $version, $enabled, $scope)
		{
			BUGSlogging::log('installing ' . $identifier . ', ' . $longname);
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModules::CLASSNAME, $classname);
  			$crit->addWhere(B2tModules::MODULE_NAME, $identifier);
  			$res = B2DB::getTable('B2tModules')->doSelectOne($crit);
  			if (!$res instanceof B2DBRow)
  			{
				$crit = new B2DBCriteria();
	  			$crit->addInsert(B2tModules::CLASSNAME, $classname);
	  			$crit->addInsert(B2tModules::DESC, $description);
	  			$crit->addInsert(B2tModules::ENABLED, ($enabled) ? 1 : 0);
	  			$crit->addInsert(B2tModules::MODULE_LONGNAME, $longname);
	  			$crit->addInsert(B2tModules::MODULE_NAME, $identifier);
	  			$crit->addInsert(B2tModules::VERSION, $version);
	  			$crit->addInsert(B2tModules::SHOW_IN_CONFIG, ($show_in_config) ? 1 : 0);
	  			$crit->addInsert(B2tModules::SHOW_IN_MENU, ($show_in_menu) ? 1 : 0);
	  			$crit->addInsert(B2tModules::SHOW_IN_USERMENU, ($show_in_usermenu) ? 1 : 0);
	  			$crit->addInsert(B2tModules::SCOPE, $scope);
	  			$res = B2DB::getTable('B2tModules')->doInsert($crit);
	  			$m_id = $res->getInsertID();
	  			$res = B2DB::getTable('B2tModules')->doSelectById($m_id);
  			}
  			else
  			{
	  			$m_id = $res->get(B2tModules::ID);
  			}
  			
  			if (!BUGScontext::getScope() instanceof BUGSscope) throw new Exception('No scope??');

  			if ($scope == BUGScontext::getScope()->getID())
  			{
	  			if (class_exists($classname))
	  			{
		  			$module = new $classname($m_id, $res);
		  			BUGScontext::addModule($module, $identifier);
		  			return $module;
	  			}
	  			else
	  			{
	  				throw new Exception('Can not load new instance of type ' . $classname . ', is not loaded');
	  			}
  			}
  			else
  			{
	  			$module = new $classname($m_id, $res);
	  			return $module;
  			}
		}
		
		public function getClassname()
		{
			return $this->_classname;
		}
		
		public function createSection($module, $identifier, $function_name, $scope)
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModuleSections::IDENTIFIER, $identifier);
			$crit->addWhere(B2tModuleSections::MODULE, $module);
			$crit->addWhere(B2tModuleSections::FUNCTION_NAME, $function_name);
			$crit->addWhere(B2tModuleSections::MODULE_NAME, $this->_name);
			$crit->addWhere(B2tModuleSections::SCOPE, $scope);
			$res = B2DB::getTable('B2tModuleSections')->doSelectOne($crit);
			if (!$res instanceof B2DBRow)
			{
				$crit = new B2DBCriteria();
				$crit->addInsert(B2tModuleSections::IDENTIFIER, $identifier);
				$crit->addInsert(B2tModuleSections::MODULE, $module);
				$crit->addInsert(B2tModuleSections::FUNCTION_NAME, $function_name);
				$crit->addInsert(B2tModuleSections::MODULE_NAME, $this->_name);
				$crit->addInsert(B2tModuleSections::SCOPE, $scope);
				$res = B2DB::getTable('B2tModuleSections')->doInsert($crit);
				BUGScontext::listenToTrigger($module, $identifier, array($this, $function_name));
				$this->setSectionEnabled($module, $identifier);
			}
			return true;
		}
		
		public function disableSection($module, $identifier, $scope)
		{
			unset($this->_enabledsections[$module . '_' . $identifier]);
			return $this->removeSection($module, $identifier, $scope);
		}
		
		public function removeSection($module, $identifier, $scope)
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModuleSections::IDENTIFIER, $identifier);
			$crit->addWhere(B2tModuleSections::MODULE, $module);
			$crit->addWhere(B2tModuleSections::MODULE_NAME, $this->_name);
			$crit->addWhere(B2tModuleSections::SCOPE, $scope);
			$res = B2DB::getTable('B2tModuleSections')->doDelete($crit);
			return true;
		}
		
		public function __toString()
		{
			return $this->_name;
		}
		
		public function __call($func, $args)
		{
			throw new Exception('Trying to call function ' . $func . '() in module ' . $this->_shortname . ', but the function does not exist');
		}
		
		public function getID()
		{
			return $this->_itemid;
		}
		
		public function getName()
		{
			return $this->_name;
		}
		
		public function getLongname()
		{
			return $this->_longname;
		}
		
		public function getMenuTitle()
		{
			return $this->_module_menu_title;
		}
		
		public function addAvailablePermission($permission_name, $description, $target = 0, $levels = 2)
		{
			$this->_availablepermissions[] = array('permission_name' => $permission_name, 'description' => $description, 'target' => $target, 'levels' => $levels);
		}
		
		public function getAvailablePermissions()
		{
			return $this->_availablepermissions;
		}
		
		public function addAvailableSection($module, $identifier, $description)
		{
			$this->_availablesections[] = array('module' => $module, 'identifier' => $identifier, 'description' => $description);
		}
		
		public function getAvailableSections()
		{
			return $this->_availablesections;
		}
		
		static public function cacheAllAccessPermissions()
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModulePermissions::SCOPE, BUGScontext::getScope()->getID());
			
			$resultset = B2DB::getTable('B2tModulePermissions')->doSelect($crit);
			while ($row = $resultset->getNextRow())
			{
				self::cacheAccessPermission($row->get(B2tModulePermissions::MODULE_NAME), $row->get(B2tModulePermissions::UID), $row->get(B2tModulePermissions::GID), $row->get(B2tModulePermissions::TID), 0, (bool) $row->get(B2tModulePermissions::ALLOWED));
			}
		}

		static public function cacheAccessPermission($module_name, $uid, $gid, $tid, $all, $allowed)
		{
			self::$_permissions[$module_name][] = array('uid' => $uid, 'gid' => $gid, 'tid' => $tid, 'all' => $all, 'allowed' => $allowed); 
		}

		public function setPermission($uid, $gid, $tid, $allowed, $scope = null)
		{
			$scope = ($scope === null) ? BUGScontext::getScope()->getID() : $scope;
			B2DB::getTable('B2tModulePermissions')->deleteByModuleAndUIDandGIDandTIDandScope($this->getName(), $uid, $gid, $tid, $scope);
			B2DB::getTable('B2tModulePermissions')->setPermissionByModuleAndUIDandGIDandTIDandScope($this->getName(), $uid, $gid, $tid, $allowed, $scope);
			if ($scope == BUGScontext::getScope()->getID())
			{
				self::cacheAccessPermission($this->getName(), $uid, $gid, $tid, 0, $allowed);
			}
		}
		
		/*public function setPermission($uid, $gid, $tid, $allowed, $insertdeny = false, $scope = 0)
		{
			if ($scope == 0)
			{
				$scope = BUGScontext::getScope()->getID();
			}
			if (is_bool($allowed))
			{
				$allowed = ($allowed) ? 1 : 0;
			}
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModulePermissions::UID, $uid);
			$crit->addWhere(B2tModulePermissions::TID, $tid);
			$crit->addWhere(B2tModulePermissions::GID, $gid);
			$crit->addWhere(B2tModulePermissions::MODULE_NAME, $this->_name);
			$crit->addWhere(B2tModulePermissions::SCOPE, $scope);
			B2DB::getTable('B2tModulePermissions')->doDelete($crit);
	
			if ($allowed == 1 || $insertdeny == 1)
			{
				$crit = new B2DBCriteria();
				$crit->addInsert(B2tModulePermissions::UID, $uid);
				$crit->addInsert(B2tModulePermissions::TID, $tid);
				$crit->addInsert(B2tModulePermissions::GID, $gid);
				$crit->addInsert(B2tModulePermissions::MODULE_NAME, $this->_name);
				$crit->addInsert(B2tModulePermissions::SCOPE, $scope);
				$crit->addInsert(B2tModulePermissions::ALLOWED, $allowed);
				$res = B2DB::getTable('B2tModulePermissions')->doInsert($crit);
			}
			$this->rebuildAccessPermissionCache();
		}*/
	
		static public function rebuildAccessPermissionCache()
		{
			self::$_permissions = array();
			self::cacheAllAccessPermissions();
		}
		
		static public function getAccessPermissionList()
		{
			if (self::$_permissions === null)
			{
				self::rebuildAccessPermissionCache();
			}
			return self::$_permissions;
		}
		
		public function hasAccess($uid = null, $gid = null, $tid = null, $all = null, $debug = false)
		{
			$permissions = self::getAccessPermissionList();
			if (!array_key_exists($this->getName(), $permissions))
			{
				throw new Exception('This modules access permission has not been cached. Something is wrong.');
			}
			$debug = false;
			if ($debug) BUGSlogging::log($this->_name);
			if ($debug)
			{
				foreach ($permissions[$this->getName()] as $aPerm)
				{
					BUGSlogging::log($aPerm);
				}
			}
			if ($all == null)
			{
				$uid = ($uid === null) ? BUGScontext::getUser()->getUID() : $uid;
				$tid = ($tid === null) ? BUGScontext::getUser()->getTeams() : $tid;
				if (!BUGScontext::getUser()->getGroup() instanceof BUGSgroup) return false;
				$gid = ($gid === null) ? BUGScontext::getUser()->getGroup()->getID() : $gid;
				
				foreach ($permissions[$this->getName()] as $aPerm)
				{
					if ($aPerm['uid'] == $uid && $uid != 0)
					{
						if ($debug) echo 'returning from uid';
						return $aPerm['allowed'];
					}
				}
				
				foreach ($permissions[$this->getName()] as $aPerm)
				{
					if ($aPerm['tid'] == $tid && $tid != 0)
					{
						if ($debug) echo 'returning from uid';
						return $aPerm['allowed'];
					}
				}
				
				foreach ($permissions[$this->getName()] as $aPerm)
				{
					if ($aPerm['gid'] == $gid && $gid != 0)
					{
						if ($debug) echo 'returning from uid';
						return $aPerm['allowed'];
					}
				}
				
			}
			
			foreach ($permissions[$this->getName()] as $aPerm)
			{
				if (($aPerm['uid'] + $aPerm['gid'] + $aPerm['tid']) == 0)
				{
					return $aPerm['allowed'];
				}
			}
			
			return false;
		}
				
		static function populateSections($module_names)
		{
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModuleSections::MODULE_NAME, $module_names, B2DBCriteria::DB_IN);
			$crit->addWhere(B2tModuleSections::SCOPE, BUGScontext::getScope()->getID());
			$crit->addWhere(B2tModules::SCOPE, BUGScontext::getScope()->getID());
			$crit->addOrderBy(B2tModuleSections::ORDER, 'asc');
			$resultset = B2DB::getTable('B2tModuleSections')->doSelect($crit);
			
			while ($row = $resultset->getNextRow())
			{
				$module = BUGScontext::getModule($row->get(B2tModuleSections::MODULE_NAME));
				if ($module->hasAccess() && $module->isEnabled())
				{
					BUGScontext::listenToTrigger($row->get(B2tModuleSections::MODULE), $row->get(B2tModuleSections::IDENTIFIER), array(BUGScontext::getModule($row->get(B2tModuleSections::MODULE_NAME)), $row->get(B2tModuleSections::FUNCTION_NAME)));
					BUGScontext::getModule($row->get(B2tModuleSections::MODULE_NAME))->setSectionEnabled($row->get(B2tModuleSections::MODULE), $row->get(B2tModuleSections::IDENTIFIER));
				}
			}
		}
		
		public function setSectionEnabled($module, $identifier)
		{
			$this->_enabledsections[$module . '_' . $identifier] = true;
		}
		
		public function getConfigTitle()
		{
			return $this->_module_config_title;
		}
		
		public function getConfigDescription()
		{
			return $this->_module_config_description;
		}
		
		public function getVersion()
		{
			return $this->_module_version;
		}
		
		/**
		 * Shortcut for the global settings function
		 * 
		 * @param string  $setting the name of the setting
		 * @param integer $uid     the uid for the user to check
		 * 
		 * @return mixed
		 */
		public function getSetting($setting, $uid = 0)
		{
			return BUGSsettings::get($setting, $this->getName(), BUGScontext::getScope()->getID(), $uid);
		}
		
		public function saveSetting($setting, $value, $uid = 0)
		{
			return BUGSsettings::saveSetting($setting, $value, $this->getName(), BUGScontext::getScope()->getID(), $uid);
		}
		
		/**
		 * Returns whether the module is visible in the menu
		 *
		 * @return boolean
		 */
		public function isVisibleInMenu()
		{
			return $this->_showinmenu;
		}
		
		/**
		 * Returns whether the module is visible in configuration
		 *
		 * @return boolean
		 */
		public function isVisibleInConfig()
		{
			return $this->_showinconfig;
		}
		
		/**
		 * Returns whether the module is visible in the user menu
		 *
		 * @return boolean
		 */
		public function isVisibleInUsermenu()
		{
			return $this->_showinusermenu;
		}
		
		/**
		 * Returns whether the module is visible in the menu
		 *
		 * @return boolean
		 */
		public function isEnabled()
		{
			return $this->_enabled;
		}
		
		public function isSectionEnabled($module, $identifier)
		{
			if ($this->_enabled && isset($this->_enabledsections[$module . '_' . $identifier]))
			{
				return true;
			}
			return false;
		}

		public function initialize()
		{
			
		}
		
		public function activate()
		{
			if ($this->_enabled == false || $this->hasAccess() == false)
			{
				bugs_showError('B2 Engine error - Not permitted', "You do not have access to this module. <br>You may have tried to access a link that is no longer in use.<br><br>If you think this is an error, please contact the administrator of this BUGS 2 instance.", true);
				exit();				
			}
		}
		
		public function getDescription()
		{
			return $this->_description;
		}
		
		static public function getAllModulePermissions($module, $uid, $tid, $gid)
		{
	
			$crit = new B2DBCriteria();
			$crit->addWhere(B2tModulePermissions::MODULE_NAME, $module);
			//$sql = "select b2mp.allowed from bugs2_modulepermissions b2mp where b2mp.module_name = '$module'";
			switch (true)
			{
				case ($uid != 0):
					//$sql .= " and uid = $uid";
					$crit->addWhere(B2tModulePermissions::UID, $uid);
				case ($tid != 0):
					//$sql .= " and tid = $tid";
					$crit->addWhere(B2tModulePermissions::TID, $tid);
				case ($gid != 0):
					//$sql .= " and gid = $gid";
					$crit->addWhere(B2tModulePermissions::GID, $gid);
			}
			if (($uid + $tid + $gid) == 0)
			{
				//$sql .= " and uid = $uid and tid = $tid and gid = $gid";
				$crit->addWhere(B2tModulePermissions::UID, $uid);
				$crit->addWhere(B2tModulePermissions::TID, $tid);
				$crit->addWhere(B2tModulePermissions::GID, $gid);
			}
			
			//$sql .= " AND b2mp.scope = " . BUGScontext::getScope()->getID();
			$crit->addWhere(B2tModulePermissions::SCOPE, BUGScontext::getScope()->getID());
	
			//$res = b2db_sql_query($sql, B2DB::getDBlink());
	
			#print $sql;
	
			$permissions = array();
			$res = B2DB::getTable('B2tModulePermissions')->doSelect($crit);
	
			while ($row = $res->getNextRow())
			{
				$permissions[] = array('allowed' => $row->get(B2tModulePermissions::ALLOWED));
			}
	
			return $permissions;
		}
	
		public function loadHelpTitle($topic)
		{
			return $topic;
		}
		
		public function getRoute()
		{
			return 'login';
		}
		
	}
