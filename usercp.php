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
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'html_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (CACHE_DIR . 'timezones.php');
dbconn(false);
loggedinorreturn();
$stdfoot = array(
    /** include js **/
    'js' => array(
        'keyboard',
    )
);
$stdhead = array(
    /** include js **/
    'js' => array(
        'custom-form-elements'
    ),
    /** include css **/
    'css' => array(
         'usercp'
    )
);
//echo user_options::CLEAR_NEW_TAG_MANUALLY;
//die();
$lang = array_merge(load_language('global') , load_language('usercp'), load_language('achievement_history'));
$newpage = new page_verify();
$newpage->create('tkepe');
$HTMLOUT = $stylesheets = $wherecatina = '';
if (isset($_GET["edited"])) {
    $HTMLOUT.= "<div class='callout success'>{$lang['usercp_updated']}!</div><br />";
    if (isset($_GET["mailsent"])) $HTMLOUT.= "<h2>{$lang['usercp_mail_sent']}!</h2>\n";
} elseif (isset($_GET["emailch"])) {
    $HTMLOUT.= "<h1>{$lang['usercp_emailch']}!</h1>\n";
}
$HTMLOUT.= "<form method='post' action='takeeditcp.php'>";
$HTMLOUT.='<div class="grid-container">
  <div class="grid-x">
    <div class="cell medium-3">';
require_once (BLOCK_DIR . 'usercp/navs.php');
$HTMLOUT.='</div>
    <div class="cell medium-9">
      <div class="tabs-content vertical" data-tabs-content="usercp-tabs">
        <div class="tabs-panel is-active" id="panel1">';
		require_once (BLOCK_DIR . 'usercp/pms.php');
$HTMLOUT.='</div>
        <div class="tabs-panel" id="panel2">';
		require_once (BLOCK_DIR . 'usercp/avatar.php');
$HTMLOUT.=' </div>
        <div class="tabs-panel" id="panel3">';
		require_once (BLOCK_DIR . 'usercp/signature.php');
$HTMLOUT.='</div>
		<div class="tabs-panel" id="panel4">';
		//require_once (BLOCK_DIR . 'usercp/awards.php');
$HTMLOUT.='</div>
		<div class="tabs-panel" id="panel5">';
		require_once (BLOCK_DIR . 'usercp/security.php');
$HTMLOUT.='</div>
        <div class="tabs-panel" id="panel6">';
require_once (BLOCK_DIR . 'usercp/location.php');
$HTMLOUT.='</div>
        <div class="tabs-panel" id="panel7">';
		require_once (BLOCK_DIR . 'usercp/torrents.php');
$HTMLOUT.='</div>
<div class="tabs-panel" id="panel8">';
require_once (BLOCK_DIR . 'usercp/social.php');
$HTMLOUT.='</div>
<div class="tabs-panel" id="panel9">';
require_once (BLOCK_DIR . 'usercp/personal.php');
$HTMLOUT.='</div>
<div class="tabs-panel" id="panel10">';
require_once (BLOCK_DIR . 'usercp/links.php');
$HTMLOUT.='</div>
      </div>
    </div>
  </div>
</div>';
$HTMLOUT.= '</form>';
echo stdhead(htmlsafechars($CURUSER["username"], ENT_QUOTES) . "{$lang['usercp_stdhead']} ", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
