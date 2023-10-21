<?php

namespace packages\acp\page;

use packages\data\repository\RepositoryList;
use wcf\page\MultipleLinkPage;

class RepositoryListPage extends MultipleLinkPage
{
    public $objectListClassName = RepositoryList::class;

    public $sortField = 'name';

    public $sortOrder = 'ASC';

    public $activeMenuItem = 'packages.acp.menu.link.package.repository.list';
}
