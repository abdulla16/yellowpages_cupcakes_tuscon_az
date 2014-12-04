<?php

class DataManager {
	
	public function saveBusiness($business) {
		scraperwiki::save_sqlite(array('name'), array('name' => $business->getName()), "data");
		foreach($business->getBranches() as $branch) {
			$this->saveBranch($branch, $business->getName());
		}
	
	}
	
	public function saveBranch($branch, $businessName) {
		$columnValues = array();
		
		$columnValues["id"] = $branch->getId();
		$columnValues["business_name"] = $businessName;
		$columnValues["street_address"] = $branch->getStreetAddress();
		$columnValues["address_locality"] = $branch->getAddressLocality();
		$columnValues["address_region"] = $branch->getAddressRegion();
		$columnValues["postal_code"] = $branch->getPostalCode();
		$object = new stdClass();
		foreach($branch->getDetails() as $column => $value) {
			$newColumn = str_replace(" ", "_" , strtolower($column));
			$object->$newColumn = $value;
		}
		
		$columnValues["additional_details"] = json_encode($object);
		var_dump($columnValues);
		scraperwiki::save_sqlite(array("id"), $columnValues, "branch");
		foreach($branch->getPhones() as $phone) {
			$this->savePhone($phone, $branch->getId());
			
		}
	}
	
	public function savePhone($telephone, $branch_id) {
		scraperwiki::save_sqlite(array("branch_id"), array("branch_id" => $branch_id, "telephone" => str_replace("'", "'", $telephone)), "telephone");
	}

}
