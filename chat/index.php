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
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author Philip Nicolcev
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Suppress errors:
error_reporting(E_ALL);
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
dbconn();

// Include custom libraries and initialization code:
require_once(AJAXCHAT_DIR . 'lib' . DIRECTORY_SEPARATOR . 'custom.php');

// Include Class libraries:
require_once(AJAXCHAT_DIR . 'lib' . DIRECTORY_SEPARATOR . 'classes.php');

// Initialize the chat:
$ajaxChat = new CustomAJAXChat();

?>

