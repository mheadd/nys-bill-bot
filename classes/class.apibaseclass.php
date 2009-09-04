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