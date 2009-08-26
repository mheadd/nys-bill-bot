<?php
/*
 * This is the base class for all API invocation classes.
 */
class apiBaseClass {
	
	// API base URL
	protected $baseURL;
	
	// CURL handler
	protected $ch;
	
	// API return info
	public $output;
	public $info;
	
	protected function __construct($baseURL) {		
		$this->baseURL = $baseURL;
		$this->ch = curl_init();		
	}
	
	public function logResults($logFile, $message) {
		$lh = fopen($logFile, 'a');
		fwrite($lh, date("F j, Y, g:i a").": API response from ".$this->baseURL." = ".$message."\n");
		fclose($lh);
	}
	
	protected function __destruct() {
		curl_close($this->ch);		
	}
}
?>