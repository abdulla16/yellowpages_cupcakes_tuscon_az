<?php

require __DIR__.'/scraperwiki.php';
require __DIR__.'/simple_html_dom.php';
require __DIR__.'/YellowPagesBusinessExtractor.php';
require __DIR__.'/DataManager.php';

echo "Scraper started!\n";

//FIXME: Ideally we should have a dependency container that is used to resolve the dependencies.
class HtmlDomLoaderFactory {
	public function make() {
		return new simple_html_dom();
	}
}

$extractor = new YellowPagesBusinessExtractor(function ($url) { return scraperwiki::scrape($url); }, 
	new HtmlDomLoaderFactory(),
	new DataManager());
$businesses = $extractor->extractAndSaveBusinesses('Tuscon, AZ', 'cupcakes');
$dataManager = new DataManager();
//foreach($businesses as $business) {
//	$dataManager->saveBusiness($business);
//}

exit(0);
