<?php

//Constants used to invoke APIs.
define("API_URL_BASE", "http://open.nysenate.gov/openleg/api/1.0/bills/id/summary/");
define("CLIGS_ENDPOINT", "http://cli.gs/api/v1/cligs/create");

//Constant used to build URL reference to bill text
define("BILL_SUMMARY_URL_BASE", "http://open.nysenate.gov/openleg/api/html/bill/");

// Function to load class files as needed.
function __autoload($class_name) {
    require_once ('classes/class.'.strtolower($class_name).'.php');
}

// Function to check the bill number submitted by the user.
function validateBillPrefix($billNumber) {
	if(strtoupper(substr($billNumber,0,1)) == 'S' || strtoupper(substr($billNumber,0,1)) == 'A') {
		return true;
	}
	return false;
}

// Simple class definition to handle malformatted bill IDs.
class BillFormatException extends Exception { }

//Function to format the bill number portion of the URL - appears to require a leading upper case 'S' or 'A'.
function buildUrl($billNumber) {
	return strtoupper($billNumber);
}

try {

  	// Set up the bot object and get the bill number submitted by the user.
	$billBot = new imified($_POST);
  	$billNumber = $billBot->getMsg();
  
  	// Make sure its a Senate or Assembly bill
  	if(!validateBillPrefix($billNumber)) {
  		throw new BillFormatException("Bill must be prefixed with an 'S' or an 'A'.");
  	}
  	else {
  		
  		// Set up the opensenate object to retrieve the bill information.
  		$getBillInfo = new opensenate(API_URL_BASE);
  		
  		// Get the bill summary
  		$getBillInfo->invoke(buildUrl($billNumber));
  		$xml = new SimpleXmlElement($getBillInfo->output);
  		$summary = $xml->bill->summary;
  		
  		// Get the sponsor name - since this is an attribute value, use XPath to get it.
  		$sponsorInfo = $xml->xpath('//bill/@sponsor');
  		$billSponsor = $sponsorInfo[0];
  		
  		// Get last action.
  		$lastAction = $xml->bill->actions->action;
  		
  		// Write out the response to the user.
		if ($billBot->getNetwork() == 'TWITTER' || $billBot->getNetwork() == 'SMS') {
			
			//Create a new Cligs object to shorten the URL.
			$cligs = new cligs(CLIGS_ENDPOINT);
			$cligs->url = BILL_SUMMARY_URL_BASE.$billNumber;
			$cligs->invoke();
			$shortUrl = $cligs->output;
			
			//Write out the response.
			echo buildUrl($billNumber)." ($billSponsor): $lastAction. $shortUrl";
		}
		else {			
    			
			//Write out the response with long URL.
			echo "Sponsor: $billSponsor<br>";
    		echo "Summary: $summary<br>";
    		echo "Last Action: $lastAction<br>";
			echo "Bill Text: ".BILL_SUMMARY_URL_BASE.$billNumber;
		}
  }
}

catch (BillFormatException $ex) {
	echo $ex->getMessage();
}

catch (Exception $ex) {
	die("Sorry. We had a problem. :-(");
}

?>
