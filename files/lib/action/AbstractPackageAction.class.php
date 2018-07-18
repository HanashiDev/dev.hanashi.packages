<?php
namespace packages\action;
use wcf\action\AbstractAction;

abstract class AbstractPackageAction extends AbstractAction {
	public $repositoryID;
	
	public $repositoryName;
	
	public function readParameters() {
		parent::readParameters();
	}
	
	public function execute() {
		
	}
}