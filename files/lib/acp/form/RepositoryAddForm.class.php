<?php
namespace packages\acp\form;
use packages\data\repository\RepositoryList;
use packages\data\repository\RepositoryAction;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

class RepositoryAddForm extends AbstractForm {
	public $activeMenuItem = 'packages.acp.menu.link.package.repository.add';
	
	protected $name;
	
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['name']))
			$this->name = $_POST['name'];
	}
	
	public function validate() {
		parent::validate();
		
		if (strlen($this->name) < 2)
			throw new UserInputException('name', 'tooShort');
		if (preg_match('/^[0-9]+$/', substr($this->name, 0, 1)))
			throw new UserInputException('name', 'noNumberOnStart');
		if (!preg_match('/^[a-z0-9]+$/', $this->name))
			throw new UserInputException('name', 'wrongFormat');
		if (strlen($this->name) > 20)
			throw new UserInputException('name', 'nameTooLong');
		
		$repositoryList = new RepositoryList();
		$repositoryList->getConditionBuilder()->add('name = ?', [$this->name]);
		$repositoryList->readObjects();
		if (count($repositoryList) > 0)
			throw new UserInputException('name', 'alreadyUsed');
	}
	
	public function save() {
		parent::save();
		
		$this->objectAction = new RepositoryAction([], 'create', ['data' => [
			'name' => $this->name
		]]);
		$this->objectAction->executeAction();
		
		$this->saved();
	}
	
	public function saved() {		
		parent::saved();
		
		WCF::getTPL()->assign('success', true);
	}
	
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'name' => $this->name
		));
	}
}