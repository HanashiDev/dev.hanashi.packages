<?php
namespace packages\data\repository;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

class Repository extends DatabaseObject implements IRouteController {
	protected static $databaseTableName = 'repository';
	
	protected static $databaseTableIndexName = 'repositoryID';
	
	public function getTitle() {
		return $this->name;
	}
	
	public function canManage() {
		return true;
	}
	
	public function getLink() {
		return LinkHandler::getInstance()->getLink(StringUtil::firstCharToUpperCase($this->name), ['forceFrontend' => true, 'application' => 'packages']);
	}
}