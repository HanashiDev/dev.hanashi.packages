<?php
namespace packages\data\repository;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;
use wcf\system\WCF;

class Repository extends DatabaseObject implements IRouteController {
	protected static $databaseTableName = 'repository';
	
	protected static $databaseTableIndexName = 'repositoryID';
	
	public function getTitle() {
		return $this->name;
	}
	
	public function canManage() {
		return true;
	}
}