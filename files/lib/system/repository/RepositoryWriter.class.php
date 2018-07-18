<?php
namespace packages\system\repository;
use wcf\util\XML;

class RepositoryWriter extends XML {
	protected $outputFile;
	
	protected $section;
	
	protected $packages = [];
	
	public function __construct($outputFile) {
		parent::__construct();
		
		$this->outputFile = $outputFile;
	}
	
	public function createSection() {
		$this->section = $this->document->createElement('section');
		$this->section->appendChild($this->createSimpleAttribute('xmlns', 'http://www.woltlab.com'));
		$this->section->appendChild($this->createSimpleAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance'));
		$this->section->appendChild($this->createSimpleAttribute('name', 'packages'));
		$this->section->appendChild($this->createSimpleAttribute('xsi:schemaLocation', 'http://www.woltlab.com https://www.woltlab.com/XSD/tornado/packageUpdateServer.xsd'));
	}
	
	public function createPackage($name, $packageName, $packageDescription, $authorName, $authorURL, $version, $versionTime, $updateType = 'install', $requiredPackages = [], $excludedPackages = [], $updateInstructions = null, $license = 'free', $isApplication = 0, $requireAuth = 'false') {
		$package = $this->document->createElement('package');
		$package->appendChild($this->createSimpleAttribute('name', $name));
		
		$package->appendChild($this->createPackageInformation($packageName, $packageDescription, $isApplication));
		$package->appendChild($this->createAuthorInformation($authorName, $authorURL));
		$package->appendChild($this->createVersions($version, $versionTime, $updateType, $requiredPackages, $excludedPackages, $updateInstructions, $license, $requireAuth));
		
		$this->packages[] = $package;
	}
	
	protected function createPackageInformation($packageName, $packageDescription, $isApplication) {
		$packageinformation = $this->document->createElement('packageinformation');
		
		$packageinformation->appendChild($this->createCDATAElement('packagename', $packageName));
		$packageinformation->appendChild($this->createCDATAElement('packagedescription', $packageDescription));
		if ($isApplication == 1)
			$packageinformation->appendChild($this->document->createElement('isapplication', 1));
		
		return $packageinformation;
	}
	
	protected function createAuthorInformation($authorName, $authorURL) {
		$authorinformation = $this->document->createElement('authorinformation');
		
		$authorinformation->appendChild($this->createCDATAElement('author', $authorName));
		$authorinformation->appendChild($this->createCDATAElement('authorurl', $authorURL));
		
		return $authorinformation;
	}
	
	protected function createVersions($version, $versionTime, $updateType, $requiredPackages, $excludedPackages, $updateInstructions, $license, $requireAuth) {
		$versions = $this->document->createElement('versions');
		
		$versions->appendChild($this->createVersion($version, $versionTime, $updateType, $requiredPackages, $excludedPackages, $updateInstructions, $license, $requireAuth));
		
		return $versions;
	}
	
	protected function createVersion($versionNr, $versionTime, $updateType, $requiredPackages, $excludedPackages, $updateInstructions, $license, $requireAuth) {
		$version = $this->document->createElement('version');
		
		$version->appendChild($this->createSimpleAttribute('name', $versionNr));
		$version->appendChild($this->createSimpleAttribute('accessible', 'true'));
		$version->appendChild($this->createSimpleAttribute('requireAuth', $requireAuth));
		if ($updateInstructions != null)
		{
			$version->appendChild($this->createFromVersions($updateInstructions));
		}
		if (count($requiredPackages) > 0)
		{
			$version->appendChild($this->createRequiredPackages($requiredPackages));
		}
		if (count($excludedPackages) > 0)
		{
			$version->appendChild($this->createExcludedPackages($excludedPackages));
		}
		$version->appendChild($this->createCDATAElement('updatetype', $updateType));
		$version->appendChild($this->createCDATAElement('timestamp', $versionTime));
		$version->appendChild($this->createCDATAElement('license', $license));
		
		return $version;
	}
	
	protected function createFromVersions($updateInstructions) {
		$fromversions = $this->document->createElement('fromversions');
		
		foreach($updateInstructions as $version => $updateInstruction)
		{
			$fromversion = $this->createCDATAElement('fromversion', $version);
			
			$fromversions->appendChild($fromversion);
		}
		
		return $fromversions;
	}
	
	protected function createRequiredPackages($requiredPackages) {
		$requiredpackages = $this->document->createElement('requiredpackages');
		
		foreach($requiredPackages as $name => $requiredPackage)
		{
			$requiredpackage = $this->createCDATAElement('requiredpackage', $requiredPackage['name']);
			$requiredpackage->appendChild($this->createSimpleAttribute('minversion', $requiredPackage['minversion']));
			$requiredpackages->appendChild($requiredpackage);
		}
		
		return $requiredpackages;
	}
	
	protected function createExcludedPackages($excludedPackages) {
		$excludedpackages = $this->document->createElement('excludedpackages');
		
		foreach($excludedPackages as $excludedPackage)
		{
			$excludedpackage = $this->createCDATAElement('excludedpackage', $excludedPackage['name']);
			$excludedpackage->appendChild($this->createSimpleAttribute('version', $excludedPackage['version']));
			$excludedpackages->appendChild($excludedpackage);
		}
		
		return $excludedpackages;
	}
	
	public function save() {
		foreach ($this->packages as $package) {
			$this->section->appendChild($package);
		}
		
		$this->document->appendChild($this->section);
		$this->document->formatOutput = true;
		$this->document->save($this->outputFile);
	}
	
	protected function createSimpleAttribute($name, $value)
	{
		$attr = $this->document->createAttribute($name);
		$attr->value = $value;
		return $attr;
	}
	
	protected function createCDATAElement($name, $value)
	{
		$element = $this->document->createElement($name);
		$element->appendChild($this->document->createCDATASection($value));
		return $element;
	}
}
