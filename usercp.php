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
require_once(CLASS_DIR . 'class_user_options.php');
require_once(CLASS_DIR . 'class_user_options_2.php');
require_once(INCL_DIR . 'user_functions.php');
require_once(INCL_DIR . 'html_functions.php');
require_once(INCL_DIR . 'bbcode_functions.php');
require_once(CLASS_DIR . 'page_verify.php');
require_once(INCL_DIR . 'pager_functions.php');
require_once(CACHE_DIR . 'timezones.php');
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
$lang = array_merge(load_language('global'), load_language('usercp'), load_language('achievement_history'), load_language('user_blocks'));
$newpage = new page_verify();
$newpage->create('tkepe');
$HTMLOUT = $stylesheets = $wherecatina = '';
$HTMLOUT.='<div class="grid-container">
  <div class="grid-x">
    <div class="cell medium-3">';
require_once(BLOCK_DIR . 'usercp/navs.php');
$HTMLOUT.='</div>
    <div class="cell medium-9">
      <div class="tabs-content vertical" data-tabs-content="usercp-tabs">';
      if (isset($_GET["edited"])) {
          $HTMLOUT.= "<div class='callout success'>{$lang['usercp_updated']}!</div><br />";
          if (isset($_GET["mailsent"])) {
              $HTMLOUT.= "<h2>{$lang['usercp_mail_sent']}!</h2>\n";
          }
      } elseif (isset($_GET["emailch"])) {
          $HTMLOUT.= "<h1>{$lang['usercp_emailch']}!</h1>\n";
      }
        require_once(BLOCK_DIR . 'usercp/pms.php');
require_once(BLOCK_DIR . 'usercp/avatar.php');
require_once(BLOCK_DIR . 'usercp/signature.php');
//require_once (BLOCK_DIR . 'usercp/awards.php');
require_once(BLOCK_DIR . 'usercp/security.php');
require_once(BLOCK_DIR . 'usercp/location.php');
require_once(BLOCK_DIR . 'usercp/torrents.php');
require_once(BLOCK_DIR . 'usercp/social.php');
require_once(BLOCK_DIR . 'usercp/personal.php');
require_once(BLOCK_DIR . 'usercp/links.php');
if ($CURUSER['got_blocks'] == 'no') {
    $HTMLOUT.= "<div class='callout alert-callout-border warning'><p class='text-center'>" . $lang['user_b_err1'] . "!</p></div>";
} else {
    require_once(BLOCK_DIR . 'usercp/user_blocks.php');
}
$HTMLOUT.='
      </div>
    </div>
    </div>
    </div>';
$HTMLOUT.= '';
echo stdhead(htmlsafechars($CURUSER["username"], ENT_QUOTES) . "{$lang['usercp_stdhead']} ", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
