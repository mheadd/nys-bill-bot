<?php
/*
 * This class is used to invoke the NY Senate OpenLeg API.
 */

if (!class_exists(apiBaseClass)) { require('base.php'); }

class opensenate extends apiBaseClass {
	
	public function __construct($baseURL) {
		parent::__construct($baseURL);
	}
	
	public function invoke($url) {
		
		$fullUrl = $this->baseURL.$url;
		
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_URL, $fullUrl);
		
		$this->output = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);
		
	}
	
	public function logResults($logFile, $message) {
		parent::logResults($logFile, $message);
	}
	
	public function __destruct() {
		parent::__destruct();
	}
}
?>
