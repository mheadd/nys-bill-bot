<?php
/*
 *  This is the IMified class: Contents of file "class.imified.php"
 */  

class imified {

// Private class members
private $userkey;
private $network;
private $user;
private $channel;
private $msg;
private $step;
private $values = Array();

/*
 * Constructor method takes the $_POST array and
 * populates the class properties
 *
 */
public function __construct(Array $postValues) {

	$this->userkey = $postValues['userkey'];
  	$this->network = $postValues['network'];
  	$this->msg = $postValues['msg'];
  	$this->step = (int) $postValues['step'];

	foreach($postValues as $key => $value) {
		if(preg_match("/^value/", $key)) {
			array_push($this->values, $value);
		}
	}
}

// Returns the IMified user key
public function getUserkey() {
	 return $this->userkey;
}

// Returns the IM network the request came from (upper cased)
public function getNetwork() {
	 return strtoupper($this->network);
}

// Returns the actual screen name of the user calling the bot
public function getUser() {
	 return $this->user;
}

// Returns the Twitter method used to contact the bot (public/private)
public function getChannel() {
	 return $this->channel;
}

// Returns the message sent by the IM user with the last request
public function getMsg() {
	 return $this->msg;
}

// Returns the current step in the conversation with the user
public function getStep() {
	 return $this->step;
}

// Returns the entire values array, which holds all values submitted by user
public function getAllValues() {
	 return $this->values;
}

// Returns a specific value in the values array, sepcified by $key
public function getValue($key) {
	 return $this->values[$key];
}

// Returns the last value in the values array
public function getLastValue() {
	 return end($this->values);
}

}

?>
