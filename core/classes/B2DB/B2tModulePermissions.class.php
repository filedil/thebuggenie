<?php

	/**
	 * Module permissions table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Module permissions table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tModulePermissions extends B2DBTable 
	{

		const B2DBNAME = 'bugs2_modulepermissions';
		const ID = 'bugs2_modulepermissions.id';
		const SCOPE = 'bugs2_modulepermissions.scope';
		const MODULE_NAME = 'bugs2_modulepermissions.module_name';
		const UID = 'bugs2_modulepermissions.uid';
		const GID = 'bugs2_modulepermissions.gid';
		const TID = 'bugs2_modulepermissions.tid';
		const ALLOWED = 'bugs2_modulepermissions.allowed';

		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addVarchar(self::MODULE_NAME, 50);
			parent::_addBoolean(self::ALLOWED);
			parent::_addForeignKeyColumn(self::UID, B2DB::getTable('B2tUsers'), B2tUsers::ID);
			parent::_addForeignKeyColumn(self::GID, B2DB::getTable('B2tGroups'), B2tGroups::ID);
			parent::_addForeignKeyColumn(self::TID, B2DB::getTable('B2tTeams'), B2tTeams::ID);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
		}
		
		public function deleteByModuleAndUIDandGIDandTIDandScope($module_name, $uid, $gid, $tid, $scope)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::MODULE_NAME, $module_name);
			$crit->addWhere(self::UID, $uid);
			$crit->addWhere(self::GID, $gid);
			$crit->addWhere(self::TID, $tid);
			$crit->addWhere(self::SCOPE, $scope);
			$res = $this->doDelete($crit);
		}
		
		public function setPermissionByModuleAndUIDandGIDandTIDandScope($module_name, $uid, $gid, $tid, $allowed, $scope)
		{
			$crit = $this->getCriteria();
			$crit->addInsert(self::MODULE_NAME, $module_name);
			$crit->addInsert(self::ALLOWED, $allowed);
			$crit->addInsert(self::UID, $uid);
			$crit->addInsert(self::GID, $gid);
			$crit->addInsert(self::TID, $tid);
			$crit->addInsert(self::SCOPE, $scope);
			$res = $this->doInsert($crit);
		}
		
	}
