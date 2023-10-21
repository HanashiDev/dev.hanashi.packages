<?php

namespace packages\system\cronjob;

use filebase\data\file\version\FileVersion;
use filebase\data\file\version\FileVersionAction;
use filebase\data\file\version\FileVersionList;
use filebase\data\file\ViewableFileList;
use packages\data\repository\Repository;
use packages\data\repository\RepositoryAction;
use packages\data\repository\RepositoryList;
use packages\system\repository\RepositoryWriter;
use wcf\data\category\CategoryList;
use wcf\data\cronjob\Cronjob;
use wcf\system\cronjob\AbstractCronjob;
use wcf\system\package\PackageArchive;
use wcf\system\package\validation\PackageValidationException;

class PackageServerUpdateCronjob extends AbstractCronjob
{
    public function execute(Cronjob $cronjob)
    {
        parent::execute($cronjob);

        $repositoryList = new RepositoryList();
        $repositoryList->readObjects();

        foreach ($repositoryList as $repository) {
            $this->createRepositoryCache($repository);
        }
    }

    protected function createRepositoryCache(Repository $repository)
    {
        $categoryList = new CategoryList();
        $categoryList->getConditionBuilder()->add('repositoryID = ?', [$repository->repositoryID]);
        $categoryList->getConditionBuilder()->add('isPackageServer = 1');
        $categoryList->readObjectIDs();

        $xml = new RepositoryWriter($repository->getCacheFile());
        $xml->createSection();

        $fileList = new ViewableFileList();
        $fileList->getConditionBuilder()->add('categoryID IN (?)', [\implode(',', $categoryList->objectIDs)]);
        $fileList->readObjects();

        $packageCounter = 0;
        foreach ($fileList as $file) {
            if ($file->getLastVersion()->filesize == 0) {
                continue;
            }

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

            $name = $archive->getPackageInfo('name');
            $packageName = $packageNameArr['default'];
            $packageDescription = '';
            if (isset($packageDescriptionArr['default'])) {
                $packageDescription = $packageDescriptionArr['default'];
            }
            $author = $archive->getAuthorInfo('author');
            $authorUrl = $archive->getAuthorInfo('authorURL');
            $isApplication = $archive->getPackageInfo('isApplication');
            $license = ($file->isCommercial == 1) ? 'commercial' : 'free';

            $fileVersionList = new FileVersionList();
            $fileVersionList->getConditionBuilder()->add('fileID = ?', [$file->fileID]);
            $fileVersionList->readObjects();

            $versions = [];
            foreach ($fileVersionList as $fileVersion) {
                $archive = new PackageArchive($fileVersion->getLocation());
                try {
                    $archive->openArchive();
                } catch (PackageValidationException $e) {
                    continue;
                }

                $versions[] = [
                    'version' => $archive->getPackageInfo('version'),
                    'timestamp' => $fileVersion->uploadTime,
                    'updateType' => ($archive->getAllUpdateInstructions() === []) ? 'install' : 'update',
                    'requiredPackages' => $archive->getRequirements(),
                    'excludedPackages' => $archive->getExcludedPackages(),
                    'instructions' => $archive->getAllUpdateInstructions(),
                    'requireAuth' => ($fileVersion->canDownload()) ? 'false' : 'true',
                ];

                $objectAction = new FileVersionAction([$fileVersion], 'update', ['data' => [
                    'packageName' => $archive->getPackageInfo('name'),
                    'packageVersion' => $archive->getPackageInfo('version'),
                    'repositoryID' => $repository->repositoryID,
                ]]);
                $objectAction->executeAction();
            }

            $xml->createPackage(
                $name,
                $packageName,
                $packageDescription,
                $author,
                $authorUrl,
                $versions,
                $isApplication,
                $license
            );

            $packageCounter++;
        }
        $objectAction = new RepositoryAction([$repository], 'update', ['data' => [
            'packesCount' => $packageCounter,
            'lastUpdateTime' => \time(),
        ]]);
        $objectAction->executeAction();

        $xml->save();
    }
}
