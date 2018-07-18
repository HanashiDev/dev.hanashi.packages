<?php
namespace packages\system\cronjob;
use filebase\data\category\FilebaseCategoryNodeTree;
use filebase\data\file\ViewableFileList;
use filebase\data\file\version\FileVersion;
use filebase\data\file\version\FileVersionAction;
use packages\data\repository\Repository;
use packages\data\repository\RepositoryList;
use packages\system\repository\RepositoryWriter;
use wcf\data\cronjob\Cronjob;
use wcf\system\cronjob\AbstractCronjob;
use wcf\system\package\validation\PackageValidationException;
use wcf\system\package\PackageArchive;

class PackageServerUpdateCronjob extends AbstractCronjob {
	public function execute(Cronjob $cronjob) {
		parent::execute($cronjob);
		
		$repositoryList = new RepositoryList();
		$repositoryList->readObjects();
		
		foreach ($repositoryList as $repository) {
			$this->createRepositoryCache($repository);
		}
	}
	
	protected function createRepositoryCache(Repository $repository) {
		$categoryIDs = [];
		$categoryTree = new FilebaseCategoryNodeTree('com.woltlab.filebase.category', 0);
		$categoryList = $categoryTree->getIterator();
		foreach ($categoryList as $category) {
			if ($category->isPackageServer == 1 && $category->respositoryID == $repository->repositoryID) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		
		$xml = new RepositoryWriter($repository->getCacheFile());
		$xml->createSection();
		
		$fileList = new ViewableFileList();
		$fileList->getConditionBuilder()->add('categoryID IN (?)', [implode(',', $categoryIDs)]);
		$fileList->readObjects();
		
		foreach ($fileList as $file) {
			if ($file->getLastVersion()->filesize > 0) {
				$fileVersion = new FileVersion($file->lastVersionID);
				$fileVersion->getFile();
				
				$archive = new PackageArchive($fileVersion->getLocation());
				try {
					$archive->openArchive();
				} catch (PackageValidationException $e) {
					continue;
				}
				
				$packageNameArr = $archive->getPackageInfo('packageName');
				$packageDescriptionArr = $archive->getPackageInfo('packageDescription');
				$xml->createPackage(
					$archive->getPackageInfo('name'),
					$packageNameArr['default'],
					$packageDescriptionArr['default'],
					$archive->getAuthorInfo('author'),
					$archive->getAuthorInfo('authorURL'),
					$archive->getPackageInfo('version'),
					$fileVersion->uploadTime,
					($archive->getInstructions('update') == null) ? 'install' : 'update',
					$archive->getRequirements(),
					$archive->getExcludedPackages(),
					$archive->getInstructions('update'),
					($file->isCommercial == 1) ? 'commercial' : 'free', 
					$archive->getPackageInfo('isApplication')
				);
				
				$objectAction = new FileVersionAction([$fileVersion], 'update', ['data' => [
					'packageName' => $archive->getPackageInfo('name'),
					'packageVersion' => $archive->getPackageInfo('version')
				]]);
				$objectAction->executeAction();
			}
		}
		
		$xml->save();
	}
}
