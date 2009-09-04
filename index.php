<?php
/*
 * Copyright 2009 Mark J. Headd
 * 
 * This file is part of IMBills
 * 
 * IMBills is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IMBills is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with IMBills.  If not, see <http://www.gnu.org/licenses/>.
 *  
 */

// Function to load class files as needed.
function __autoload($class_name) {
    require_once ('classes/class.'.strtolower($class_name).'.php');
}

// Constants used to invoke APIs.
define("API_URL_BASE", "http://open.nysenate.gov/openleg/api/1.0/bills/id/summary/");
define("CLIGS_ENDPOINT", "http://cli.gs/api/v1/cligs/create");

// Constant used to build URL reference to bill text.
define("BILL_SUMMARY_URL_BASE", "http://open.nysenate.gov/openleg/api/html/bill/");

// Constants defining user agent strings to format output for different clients.
define("IM_SMS_TXT_USER_AGENT_STRING", "Railo (CFML Engine)");
define("VOICE_USER_AGENT_STRING", "Voxeo-VCS/8.0");

// Standard prefixs for error messages.
define("IM_SMS_TXT_USER_AGENT_ERROR_PREFIX", "An error occured. ");
define("VOICE_USER_AGENT_ERROR_PREFIX", "billInfoLookupFailure\nreason=");

/*
********************************************************************************
* Response format methods.
******************************************************************************** 
*/

// Function to format a response for text-based user agents.
function formatTextResponse($postArray) {
	
	// Set up the bot object and get the bill number submitted by the user.
	$billBot = new imified($_POST);
  	$billNumber = $billBot->getMsg();
  
  	// Make sure its a Senate or Assembly bill.
  	if(!validateBillPrefix($billNumber)) {
  		throw new billFormatException("Bill must be prefixed with an 'S' or an 'A'.", 0);
  	}
  	
  	try {
  		$xml = new SimpleXmlElement(lookUpBIllInfo($billNumber));
  		$summary = $xml->bill->summary;
	  		
  		// Get the sponsor name - since this is an attribute value, use XPath to get it.
  		$sponsorInfo = $xml->xpath('//bill/@sponsor');
  		$billSponsor = $sponsorInfo[0];
	  		
  		// Get last action.
  		$lastAction = $xml->bill->actions->action;
	  		
  		// Construct the response to write out the user.
		if ($billBot->getNetwork() == 'TWITTER' || $billBot->getNetwork() == 'SMS') {
			//Shorten the URL to the bill summary if a limited display device.
			$shortUrl = shortenUrl($billNumber);				
			// Write out the response.
			$response =  buildUrl($billNumber)." ($billSponsor): $lastAction. $shortUrl";
		}
		else {    			
			// Construct response with long URL.
			$response = "Sponsor: $billSponsor<br>";
	    	$response .= "Summary: $summary<br>";
	    	$response .= "Last Action: $lastAction<br>";
			$response .= "Bill Text: ".BILL_SUMMARY_URL_BASE.$billNumber;			
  		}
		return $response;
  	}
  	
  	catch (billDoesNotExistException $ex) {  		
  		throw new billFormatException($ex->getMessage(), 0);
  	}		
	
}

// Function to format a response for voice-based user agents.
function formatVoiceResponse($billNumber) {
	
	// Make sure its a Senate or Assembly bill.
  	if(!validateBillPrefix($billNumber)) {
  		throw new billFormatException("Bill must be prefixed with an 'S' or an 'A'.", 1);
  	}
  		
  	try {
  		$xml = new SimpleXmlElement(lookUpBIllInfo($billNumber));
  		// Get the sponsor name.
  		$sponsorInfo = $xml->xpath('//bill/@sponsor');
  		$billSponsor = $sponsorInfo[0];
	  		
  		// Get last action.
  		$lastAction = $xml->bill->actions->action;
  		
  		// Construct the response.
  		$response = "billInfoLookupSuccess\n";
  		$response .= "billSponsor=".$billSponsor."\n";
  		$response .= "lastAction=".$lastAction."\n";

  		return $response;
  	}
  	
  	catch (billDoesNotExistException $ex) {  		
  		throw new billFormatException($ex->getMessage(), 1);
  	}
}

/*
********************************************************************************
* Utility methods
********************************************************************************
*/

// Function to invoke the NY Senate API and get bill information.
function lookUpBIllInfo($billNumber) {
	// Set up the opensenate object to retrieve the bill information.
  	$getBillInfo = new openSenate(API_URL_BASE);
  		
  	// Invoke the API
  	$getBillInfo->invoke(buildUrl($billNumber));
  		
  	if($getBillInfo->info["http_code"] != '200') {
		throw new billDoesNotExistException("A bill with that ID does not appear to exist.");
  	}
  	else {
  		return $getBillInfo->output;
  	}
}

// Function to check the bill number submitted by the user.
function validateBillPrefix($billNumber) {
	if(strtoupper(substr($billNumber,0,1)) == 'S' || strtoupper(substr($billNumber,0,1)) == 'A') {
		return true;
	}
	return false;
}

// Function to format the bill number portion of the URL - appears to require a leading upper case 'S' or 'A'.
function buildUrl($billNumber) {
	return strtoupper($billNumber);
}

// Function to shorten a URL for limited display devices.
function shortenUrl($billNumber) {
	$cligs = new cligs(CLIGS_ENDPOINT);
	$cligs->url = BILL_SUMMARY_URL_BASE.$billNumber;
	$cligs->invoke();
	return $cligs->output;
}

// Format an exception message.
function formatExceptionMessage($message, $code) {
	if ($code) {
		return VOICE_USER_AGENT_ERROR_PREFIX.$message;
	}
	return IM_SMS_TXT_USER_AGENT_ERROR_PREFIX.$message;	
}

/*
********************************************************************************
* Main try-catch block - this is where the magic happens!
********************************************************************************
*/
 
try {
	
	// Determine user agent making the request
	if ($_SERVER['HTTP_USER_AGENT'] == IM_SMS_TXT_USER_AGENT_STRING) {
		echo formatTextResponse($_POST);
	}
	else if ($_SERVER['HTTP_USER_AGENT'] == VOICE_USER_AGENT_STRING) {
		echo formatVoiceResponse($_POST['billNumber']);
	}
	else {
		throw new invalidUserAgentException("The application can not accept requests from user agent type: ".$_SERVER['HTTP_USER_AGENT'], 0);
	}  	
}

// Exception handlers
catch (billFormatException $ex) {	
	echo(formatExceptionMessage($ex->getMessage(), $ex->getCode()));
	die;
}

catch (invalidUserAgentException $ex) {
	echo(formatExceptionMessage($ex->getMessage(), $ex->getCode()));
	die;
}

catch (Exception $ex) {
	echo(formatExceptionMessage("Something unexpected occured.", $ex->getCode()));
	die;
}

?>
