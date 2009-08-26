<?php
/*
 * This class is used to invoke the cligs API, to shorten URLs before posting to Twitter or SMS.
 */

if (!class_exists(apiBaseClass)) { require('base.php'); }

class cligs extends apiBaseClass {
	
	// Parameters that can be passed with Cligs API invokation
	public $url;
	public $title;
	public $key;
	public $appid;	
	
	public function __construct($baseURL) {
		parent::__construct($baseURL);
	}
	
	public function invoke() {
		
		$data = $this->baseURL.'?url='.$this->url.'&title='.$this->title.'&key='.$this->key.'&appid='.$this->appid;
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_URL, $data);
		
		$this->output = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);
		
	}
	
	public function logResults($logFile, $message) {
		parent::logResults($logFile, $message);
	}
	
	public function __destruct() {
		curl_close($this->ch);		
	}
	
}

?>
