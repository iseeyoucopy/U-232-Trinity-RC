<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
// Chat bot messages
if(stripos($text, 'hello') !== false) {
	// KEYWORDS TRIGGER START
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Hello, @".$this->getUserName()."! Whats Up?\n" //This is what the chatbot says when the visitor enters "hello" anywhere in a sentence!
	);
}

if(stripos($text, 'hi') !== false) {
	// KEYWORDS TRIGGER START
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Hi @".$this->getUserName().". Have a nice day!\n" //This is what the chatbot says when the visitor enters "hello" anywhere in a sentence!
	);
}

if(stripos($text, ':hmmm:') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Dont Think To Hard!.......You Might Hurt Yourself!\n" 
	);
}

if(stripos($text, 'System') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Are You Talking To Me?\n" 
	);
}

if(stripos($text, ':laugh:') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Whats so funny?.....Im Guessing Your Face!\n" 
	);
}

if(stripos($text, ':whistle:') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":blush: Awww You Whistling At My Sexy Robot Parts?\n" 
	);
}

if(stripos($text, 'NEW UPLOAD') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":w00t:\n" 
	);
}

if(stripos($text, 'NEW INTERNAL UPLOAD') !== false) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":w00t:\n" 
	);
}
?>
