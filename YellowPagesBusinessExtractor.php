<?php

require __DIR__."/Business.php";
require __DIR__."/BusinessExtractorInterface.php";

class YellowPagesBusinessExtractor implements BusinessExtractorInterface {
	
	private $htmlDomLoaderFactory;
	private $scrapeFunction;
	private $dataManager;
	private static $baseUrl = "http://www.yellowpages.com";
	public function __construct($scrapeFunction, $htmlDomLoaderFactory, $dataManager) {
		$this->scrapeFunction = $scrapeFunction;
		$this->htmlDomLoaderFactory = $htmlDomLoaderFactory;
		$this->dataManager = $dataManager;
	}
	/**
	 *
	 * @see BusinessExtractorInterface::extractBusinesses($url)
	 */
	public function extractAndSaveBusinesses($location, $searchTerms) {
		$location = urlencode($location);
		$searchTerms = urlencode($searchTerms);
		$moreResults = true;
		
		$page = 4;
		$businesses = array();
		while($page < 5) {
			sleep(1);
			
			$html = call_user_func($this->scrapeFunction, YellowPagesBusinessExtractor::$baseUrl."/search?search_terms=$searchTerms&geo_location_terms=$location&page=$page");
			$htmlDomLoader = $this->htmlDomLoaderFactory->make();
			$htmlDomLoader->load($html);
			
			$moreResults = false;
			$organicResults = $htmlDomLoader->find("div.organic");
			
			if(count($organicResults) > 0) {
				$moreResults = true;
				foreach($organicResults[0]->find("div.result") as $searchResult) {
					$name = $this->extractItem($searchResult, 'name');
					echo "Extracting business: $name...\n";
					$businessBranch = new BusinessBranch();
					$businessBranch->setStreetAddress($this->extractItem($searchResult, 'streetAddress'));
					$businessBranch->setAddressLocality($this->extractItem($searchResult, 'addressLocality'));
					$businessBranch->setAddressRegion($this->extractItem($searchResult, 'addressRegion'));
					$businessBranch->setPostalCode($this->extractItem($searchResult, 'postalCode'));
					$businessBranch->setPhones($this->extractPhones($searchResult));
					
					$id = $searchResult->getAttribute("data-ypid");
					
					$businessBranch->setId($id);
					
					$url = $this->extractDetailsURL($searchResult);
					
					if(strlen($url) > 0) {
						if($name != "Shari's Berries")
						      $this->extractDetails($businessBranch, $url);
					}	
					
					if(isset($businesses[$name])) {
						$businesses[$name]->addBranch($businessBranch);
					} else {
						$business = new Business();
						$business->setName($name);
						$business->addBranch($businessBranch);
						$businesses[$name] = $business;
					}
					$this->dataManager->saveBusiness($business);
				}
				
			}
			$page++;
		}
		return $businesses;
	}
	
	private function extractDetails(BusinessBranch &$branch, $detailsURL) {
		echo "Extracting details: $detailsURL...\n";
					
		$html = call_user_func($this->scrapeFunction, YellowPagesBusinessExtractor::$baseUrl.$detailsURL);
		$htmlDomLoader = $this->htmlDomLoaderFactory->make();
		$htmlDomLoader->load($html);
		
		$dl = $htmlDomLoader->find("dl");
		
		$details = array();
		if(count($dl) > 0) {
			$dl = $dl[0];
			$children = $dl->childNodes();
			for($i = 0; $i < count($children); $i++) {
				$child = $children[$i];
				//The dt element contains the property description (e.g., hours)
				if($child->tag == 'dt') {

					$property = $child->innertext();
					$property = trim($property, " ");
					$property = trim($property, ":");

					
					//The dd element contains the value (e.g., Mon-Sun 9am - 5pm)
					//We need to skip the other elements
					while($i < count($children) - 1) {
						$i++;
						$child = $children[$i];
						if($child->tag == 'dd') {
							$value = $child->innertext();
							if(!(strpos(strtolower($property), 'hours') !== FALSE && 
									strpos(strtolower($value), 'do you know the hours for this business?') !== FALSE)) {
								$details[$property] = $value;
							}
							break;
						}
					}
				}
			}
		}
		$branch->setDetails($details);
	}
	
	private function extractDetailsURL($searchResult) {
		$url = "";
		$item = $searchResult->find("a.business-name");
		if(count($item) > 0) {
			$item = $item[0];
			$url = $item->getAttribute("href");
		}
		return $url;
	}
	private function extractItem($searchResult, $itemprop) {
		$result = "";
		$item = $searchResult->find("[itemprop='".$itemprop."']");
		if(count($item) > 0) {
			$item = $item[0];
			$result = $item->innertext();
			$result = trim($result, " ");
			$result = trim($result, ",");
		}
		return $result;
	}
	
	private function extractPhones($searchResult) {
		echo "Extracting phones:...\n";
		
		$phones = array();
		$phones = $searchResult->find("[itemprop='telephone']");
		foreach($phones as $phone) {
			$phones[] = $phone->innertext();
		}
		return $phones;
	}
}
