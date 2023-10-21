<?php

namespace packages\system\event\listener;

use filebase\acp\form\CategoryEditForm;
use packages\data\repository\RepositoryList;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

class FilebaseCategoryEditFormListener implements IParameterizedEventListener
{
    protected $packageServer;

    protected $repository;

    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        if ($eventName == 'readFormParameters') {
            if (isset($_POST['values']['packageServer'])) {
                $this->packageServer = $_POST['values']['packageServer'];
            }
            if (isset($_POST['repository'])) {
                $this->repository = $_POST['repository'];
            }
        } elseif ($eventName == 'validate') {
            if ($this->packageServer == 1) {
                if (empty($this->repository)) {
                    throw new UserInputException('repository', 'empty');
                }

                $repositoryList = new RepositoryList();
                $repositoryList->getConditionBuilder()->add('repositoryID = ?', [$this->repository]);
                $repositoryList->readObjects();

                if (\count($repositoryList) == 0) {
                    throw new UserInputException('repository', 'notExist');
                }
            }
        } elseif ($eventName == 'save') {
            if ($this->packageServer == 1) {
                $eventObj->additionalFields = \array_merge($eventObj->additionalFields, [
                    'isPackageServer' => $this->packageServer,
                    'repositoryID' => $this->repository,
                ]);
            } elseif ($this->packageServer == 0) {
                $eventObj->additionalFields = \array_merge($eventObj->additionalFields, [
                    'isPackageServer' => $this->packageServer,
                    'repositoryID' => null,
                ]);
            }
        } elseif ($eventName == 'assignVariables') {
            if (empty($_POST) && $eventObj instanceof CategoryEditForm) {
                $this->packageServer = $eventObj->category->isPackageServer;
                $this->repository = $eventObj->category->repositoryID;
            }

            $repositoryList = new RepositoryList();
            $repositoryList->readObjects();

            WCF::getTPL()->assign([
                'packagesFilebaseAdd' => true,
                'repositoryList' => $repositoryList,
                'packageServer' => $this->packageServer,
                'repositoryID' => $this->repository,
            ]);
        }
    }
}
