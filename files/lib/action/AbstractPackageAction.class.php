<?php
namespace packages\action;
use filebase\data\file\version\FileVersionList;
use packages\data\repository\Repository;
use wcf\data\user\User;
use wcf\action\AbstractAction;
use wcf\system\exception\UserInputException;
use wcf\system\user\authentication\UserAuthenticationFactory;
use wcf\system\WCF;

abstract class AbstractPackageAction extends AbstractAction {
	public $repositoryID;
	
	public $repositoryName;
	
	public function readParameters() {
		parent::readParameters();
	}
	
	public function execute() {
		if (isset($_REQUEST['packageName']) && isset($_REQUEST['packageVersion'])) {
			WCF::getSession()->changeUser(new User(null, ['username' => 'System', 'userID' => 0]), true);
			$this->downloadFile($_REQUEST['packageName'], $_REQUEST['packageVersion']);
			return;
		}
		
		$repository = new Repository($this->repositoryID);
		if ($repository->repositoryID != $this->repositoryID) {
			echo 'Repository does not exist.';
			return;
		}
		
		$cacheFile = $repository->getCacheFile();
		if (!file_exists($cacheFile)) {
			echo 'Cache file does not exist.';
			return;
		}
		header('Content-type: application/xml');
		echo file_get_contents($repository->getCacheFile());
	}
	
	protected function downloadFile($packageName, $packageVersion) {
		$fileVersionList = new FileVersionList();
		$fileVersionList->getConditionBuilder()->add('packageName = ? AND packageVersion = ?', [$packageName, $packageVersion]);
		$fileVersionList->readObjects();
		
		foreach ($fileVersionList as $fileVersion) {
			if ($fileVersion->repositoryID != $this->repositoryID) {
				echo 'File not found';
				return;
			}
			
			$this->getFile($fileVersion);
			return;
		}
		echo 'File not found';
	}
	
	protected function getFile($fileVersion) {
		$this->authenticate($fileVersion);
		
		$location = $fileVersion->getLocation();
		if (!file_exists($location)) {
			echo 'File not found';
			return;
		}
		header('Content-type: '.$fileVersion->fileType);
		header('Content-disposition: attachment; filename="'.$fileVersion->filename.'"');
		echo file_get_contents($location);
	}
	
	protected function authenticate($fileVersion) {
		if (!$fileVersion->canDownload() && !(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER["PHP_AUTH_PW"]))) {
			$this->authHeader();
		} else if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER["PHP_AUTH_PW"])) {
			try {
				$user = UserAuthenticationFactory::getInstance()->getUserAuthentication()->loginManually($_SERVER['PHP_AUTH_USER'], $_SERVER["PHP_AUTH_PW"]);
			} catch (UserInputException $e) {
				$this->authHeader();
			}
			WCF::getSession()->changeUser($user);
			if (!$fileVersion->canDownload()) {
				$this->authHeader();
			}
		}
	}
	
	protected function authHeader() {
		header('WWW-Authenticate: Basic realm="PackageServer"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Not authenticated.';
		exit;
	}
}