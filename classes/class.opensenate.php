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
 * This class is used to invoke the NY Senate OpenLeg API.
 */

if (!class_exists(apiBaseClass)) { require('base.php'); }

class openSenate extends apiBaseClass {
	
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
