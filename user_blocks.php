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
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once(INCL_DIR . 'html_functions.php');
require_once(INCL_DIR . 'user_functions.php');
require_once(CLASS_DIR . 'page_verify.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('user_blocks'));
$stdhead = array(
        /** include css **/
        'css' => array(
                'bbcode'
        )
);
$stdfoot = array(
        /** include js **/
        'js' => array(
                /*'gallery',*/
        )
);
if ($CURUSER['got_blocks'] == 'no') {
        stderr($lang['gl_error'], $lang['user_b_err1']);
        die;
}
$newpage = new page_verify();
$newpage->create('tkeepe');
$possible_actions = array(
        'index_options',
        'userdetils_options',
        'stdhead_options'
);
$action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : '';
if (!in_array($action, $possible_actions)) stderr('ERROR', 'Incorect access');
$HTMLOUT = '';
if (isset($_GET["edited"])) {
        $HTMLOUT .= "<br /><div class='alert alert-success span11'>Updated!</div><br />";
}
$HTMLOUT .= "
<form action='takeedit_userblocks.php' method='post'>
<table class='striped'>";
if ($action == "stdhead_options") {
        //require_once (BLOCK_DIR . 'user_blocks/stdhead_bl.php');
} elseif ($action == "userdetails_options") {
        //require_once (BLOCK_DIR . 'user_blocks/userdetails_bl.php');
}
if ($action == "index_options")
        require_once(BLOCK_DIR . 'user_blocks/index_bl.php');
$HTMLOUT .= "</form>";
echo stdhead($lang['user_b_echo'], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
