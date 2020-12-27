<?php
// Chat bot messages
if(stristr($text, 'hello')) {
	// KEYWORDS TRIGGER START
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Hello, @".$this->getUserName()."! Whats Up?\n" //This is what the chatbot says when the visitor enters "hello" anywhere in a sentence!
	);
}

if(stristr($text, 'hi')) {
	// KEYWORDS TRIGGER START
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Hi @".$this->getUserName().". Have a nice day!\n" //This is what the chatbot says when the visitor enters "hello" anywhere in a sentence!
	);
}

if(stristr($text, ':hmmm:')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Dont Think To Hard!.......You Might Hurt Yourself!\n" 
	);
}

if(stristr($text, '@U-232_Bot')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Are You Talking To Me?\n" 
	);
}

if(stristr($text, ':laugh:')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		"Whats so funny?.....Im Guessing Your Face!\n" 
	);
}

if(stristr($text, ':whistle:')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":blush: Awww You Whistling At My Sexy Robot Parts?\n" 
	);
}

if(stristr($text, 'NEW UPLOAD')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":w00t:\n" 
	);
}

if(stristr($text, 'NEW INTERNAL UPLOAD')) {
    $this->insertChatBotMessage(
	$this->getChannel(),
		":w00t:\n" 
	);
}
?>
