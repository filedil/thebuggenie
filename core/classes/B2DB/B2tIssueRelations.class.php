<?php

	/**
	 * Issue relations table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Issue relations table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tIssueRelations extends B2DBTable 
	{

		const B2DBNAME = 'bugs2_issuerelations';
		const ID = 'bugs2_issuerelations.id';
		const SCOPE = 'bugs2_issuerelations.scope';
		const PARENT_ID = 'bugs2_issuerelations.parent_id';
		const CHILD_ID = 'bugs2_issuerelations.child_id';
		const MUSTFIX = 'bugs2_issuerelations.mustfix';

		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addBoolean(self::MUSTFIX);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
			parent::_addForeignKeyColumn(self::PARENT_ID, B2DB::getTable('B2tIssues'), B2tIssues::ID);
			parent::_addForeignKeyColumn(self::CHILD_ID, B2DB::getTable('B2tIssues'), B2tIssues::ID);
		}
		
		public function getRelatedIssues($issue_id)
		{
			$crit = $this->getCriteria();
			$ctn = $crit->returnCriterion(self::PARENT_ID, $issue_id);
			$ctn->addOr(self::CHILD_ID, $issue_id);
			$crit->addWhere($ctn);
			$crit->addWhere(B2tIssues::DELETED, 0);
			$res = $this->doSelect($crit);
			return $res;
		}
		
		public function getIssueRelation($this_issue_id, $related_issue_id)
		{
			$crit = $this->getCriteria();
			$ctn = $crit->returnCriterion(self::PARENT_ID, $this_issue_id);
			$ctn->addOr(self::CHILD_ID, $this_issue_id);
			$ctn->addWhere($ctn);
			$ctn = $crit->returnCriterion(self::PARENT_ID, $related_issue_id);
			$ctn->addOr(self::CHILD_ID, $related_issue_id);
			$ctn->addWhere($ctn);
			$crit->addWhere(B2tIssues::DELETED, 0);
			$res = $this->doSelectOne($crit);
			return $res;
		}
		
		public function addParentIssue($issue_id, $parent_id)
		{
			$crit = $this->getCriteria();
			$crit->addInsert(self::CHILD_ID, $issue_id);
			$crit->addInsert(self::PARENT_ID, $parent_id);
			$crit->addInsert(self::SCOPE, BUGScontext::getScope()->getID());
			$res = $this->doInsert($crit);
			return $res;
		}
		
		public function addChildIssue($issue_id, $child_id)
		{
			$crit = $this->getCriteria();
			$crit->addInsert(self::PARENT_ID, $issue_id);
			$crit->addInsert(self::CHILD_ID, $child_id);
			$crit->addInsert(self::SCOPE, BUGScontext::getScope()->getID());
			$res = $this->doInsert($crit);
			return $res;
		}
		
	}
