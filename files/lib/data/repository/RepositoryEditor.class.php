<?php
namespace packages\data\repository;
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

class RepositoryEditor extends DatabaseObjectEditor {
	protected static $baseClass = Repository::class;
}