<?php

class BusinessBranch {
	/**
	 * The business id from the scraped URL
	 * @var integer
	 */
	private $id;
	
	/**
	 *
	 * @var string
	 */
	private $streetAddress;
	
	/**
	 *
	 * @var string
	 */
	private $addressLocality;
	
	/**
	 * For example, the State
	 * @var string
	 */
	private $addressRegion;
	
	/**
	 *
	 * @var string
	 */
	private $postalCode;
	
	/**
	 * An array of strings. The list of phones for the business
	 * @var array
	 */
	private $phones;
	
	private $details;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getStreetAddress() {
		return $this->streetAddress;
	}
	
	public function setStreetAddress($streetAddress) {
		$this->streetAddress = $streetAddress;
	}
	
	public function getAddressLocality() {
		return $this->addressLocality;
	}
	
	public function setAddressLocality($addressLocality) {
		$this->addressLocality = $addressLocality;
	}
	
	public function getAddressRegion() {
		return $this->addressRegion;
	}
	
	public function setAddressRegion($addressRegion) {
		$this->addressRegion = $addressRegion;
	}
	
	public function getPostalCode() {
		return $this->postalCode;
	}
	
	public function setPostalCode($postalCode) {
		$this->postalCode = $postalCode;
	}
	
	public function getPhones() {
		return $this->phones;
	}
	
	public function setPhones($phones) {
		$this->phones = $phones;
	}

	public function setDetails($details) {
		$this->details = $details;
	}
	
	public function getDetails() {
		return $this->details;
	}

}