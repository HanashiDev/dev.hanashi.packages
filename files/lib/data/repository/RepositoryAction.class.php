<?php
namespace packages\data\repository;
use packages\system\repository\RepositoryActionHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\ISearchAction;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

class RepositoryAction extends AbstractDatabaseObjectAction {
	public function validateDelete() {
		parent::validateDelete();
        
		foreach ($this->objects as $object) {
			if(!$object->canManage()) {
				throw new PermissionDeniedException();
			}
		}
	}
	
	public function create() {
		$repository = parent::create();
		
		$repositoryActionHandler = new RepositoryActionHandler($repository);
		$repositoryActionHandler->create();
		return $repository;
	}
}