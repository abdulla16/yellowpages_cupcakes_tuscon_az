<?php


require __DIR__."/BusinessBranch.php";

/**
 * @author Abdulla Al-Qawasmeh
 *
 */
class Business {
	
	/**
	 * The name of the business
	 * @var string
	 */
	private $name;
	
	private $branches;
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getBranches() {
		return $this->branches;
	}
	
	public function addBranch($branch) {
		if(!isset($this->branches)) {
			$this->branches = array();
		}
		$this->branches[] = $branch;
	}
}