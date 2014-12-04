<?php

interface BusinessExtractorInterface {
	
	/**
	 * 
	 * @param string $url
	 * @return Array an array of Business objects
	 */
	function extractAndSaveBusinesses($location, $search_terms);
}