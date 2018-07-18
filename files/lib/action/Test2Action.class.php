<?php
namespace packages\action;
use filebase\data\file\ViewableFileList;
use filebase\data\file\version\FileVersion;
use filebase\data\file\version\FileVersionAction;
use packages\system\repository\RepositoryWriter;
use wcf\action\AbstractAction;
use wcf\system\package\validation\PackageValidationException;
use wcf\system\package\PackageArchive;

class Test2Action extends AbstractAction {
	public function execute() {
		parent::execute();
		
		$xml = new RepositoryWriter('/home/wsctest/www/httpsdocs/tmp/test.xml');
		$xml->createSection();
		
		$fileList = new ViewableFileList();
		// $fileList->getConditionBuilder()->add('categoryID = 2'); // TODO: wo Repo ausgewählt ist :P
		$fileList->readObjects();
		
		foreach ($fileList as $file) {
			if ($file->getLastVersion()->filesize > 0) {
				echo "<pre>";
				$fileVersion = new FileVersion($file->lastVersionID);
				$fileVersion->getFile();
				
				$archive = new PackageArchive($fileVersion->getLocation());
				try {
					$archive->openArchive();
				} catch (PackageValidationException $e) {
					continue;
				}
				// print_r($archive);
				
				// print_r($archive->getPackageInfo('name'));
				
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
				echo "</pre>";
			}
		}
		
		$xml->save('/home/wsctest/www/httpsdocs/tmp/test.xml');
	}
}