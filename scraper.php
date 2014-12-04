<?php

require __DIR__.'/scraperwiki.php';
require __DIR__.'/simple_html_dom.php';
require __DIR__.'/YellowPagesBusinessExtractor.php';
require __DIR__.'/DataManager.php';

//FIXME: Ideally we should have a dependency container that is used to resolve the dependencies.
class HtmlDomLoaderFactory {
	public function make() {
		return new simple_html_dom();
	}
}
// function exception_error_handler($errno, $errstr, $errfile, $errline ) {
// 	echo "Error: $errno, $errstr, $errfile, $errline\n";
// 	exit(1);
// }
// set_error_handler("exception_error_handler");

$extractor = new YellowPagesBusinessExtractor(function ($url) { return scraperwiki::scrape($url); }, 
	new HtmlDomLoaderFactory(),
	new DataManager());
$businesses = $extractor->extractAndSaveBusinesses('Tuscon, AZ', 'cupcakes');

// // $business = new Business();
// // $branch = new BusinessBranch();
// // $business->setName("testing");
// // $branch->setId('123456');
// // $branch->setAddressLocality("address locality");
// // $branch->setAddressRegion("address region");
// // $branch->setPhones(array("phone1", "phone2"));
// // $branch->setDetails(array("hours" => "Mon-Sun 9-5"));
// // $branch->setPostalCode("12345");
// // $branch->setStreetAddress("street address");
// // $business->addBranch($branch);
// // $businesses[] = $business;


 $result = scraperwiki::select("* from branch");
 var_dump($result);
// You don't have to do things with the ScraperWiki library. You can use whatever is installed
// on Morph for PHP (See https://github.com/openaustralia/morph-docker-php) and all that matters
// is that your final data is written to an Sqlite database called data.sqlite in the current working directory which
// has at least a table called data.
?>
