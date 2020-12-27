<?php
/*
 |--------------------------------------------------------------------------|
 |   https://github.com/iseeyoucopy/                                        |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2020 U-232 Codename Trinity                              |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: iseeyoucopy, stonebreath, GodFather                   |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
*/
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('chat'));
$nick = ($CURUSER ? $CURUSER['username'] : '');
$irc_url = 'irc.us.mibbit.net';
$irc_channel = '#09source';
$irc_network = '09source';
$HTMLOUT = '';
$HTMLOUT.= "<p>{$lang['chat_channel']}<a href='irc://{$irc_url}/{$irc_channel}'>{$irc_network}</a> {$lang['chat_on']} {$irc_network} {$lang['chat_network']}</p>
<iframe src='https://kiwiirc.com/client/{$irc_url}/?nick={$nick}&theme=cli{$irc_channel}' style='border:0; width:100%; height:450px;'></iframe>";
///////////////////// HTML OUTPUT ////////////////////////////
echo stdhead("{$lang['chat_chat']}").$HTMLOUT.stdfoot();
?>
