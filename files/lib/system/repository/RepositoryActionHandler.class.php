<?php
namespace packages\system\repository;
use packages\data\repository\Repository;
use wcf\util\StringUtil;

class RepositoryActionHandler {
	protected $repository;
	
	public function __construct(Repository $repository) {
		$this->repository = $repository;
	}
	
	public function create() {
		$templatePath = PACKAGES_DIR.'lib/action/PackageAction.class.template';
		$template = file_get_contents($templatePath);
		
		$className = StringUtil::firstCharToUpperCase($this->repository->name);
		$template = str_replace('{className}', $className, $template);
		$template = str_replace('{repositoryID}', $this->repository->repositoryID, $template);
		$template = str_replace('{repositoryName}', $this->repository->name, $template);
		
		$classPathName = PACKAGES_DIR.'lib/action/'.$className.'Action.class.php';
		
		file_put_contents($classPathName, $template);
	}
	
	public function delete() {
		$className = StringUtil::firstCharToUpperCase($this->repository->name);
		$classPathName = PACKAGES_DIR.'lib/action/'.$className.'Action.class.php';
		
		if (file_exists($classPathName))
			unlink($classPathName);
	}
}
