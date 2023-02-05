<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -------  @copyright  U-232 Team  ------------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
// === shoutbox 09
if ($CURUSER['opt1'] & user_options::SHOW_SHOUT) {
$commandbutton = $refreshbutton = $smilebutton = $custombutton = $staffsmiliebutton = '';
if ($CURUSER['class'] >= UC_STAFF) {
$staffsmiliebutton.= "<a href=\"javascript:PopStaffSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_ssmilies']}</a>";
}
if (get_smile() != 0) $custombutton.= "
<a href=\"javascript:PopCustomSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_csmilies']}</a>";
if ($CURUSER['class'] >= UC_STAFF) {
$commandbutton = "<a href=\"javascript:popUp('shoutbox_commands.php')\">{$lang['index_shoutbox_commands']}</a>\n";
}
$refreshbutton = "<a href='shoutbox.php' target='shoutbox'>{$lang['index_shoutbox_refresh']}</a>\n";
$smilebutton = "<a href=\"javascript:PopMoreSmiles('shbox','shbox_text')\">{$lang['index_shoutbox_smilies']}</a>\n";
$HTMLOUT .= "<div class='card'>";
    $HTMLOUT .= "<div class='card-divider'>";
$HTMLOUT.= "{$lang['index_shoutbox_general']}";
if ($CURUSER['class'] >= UC_STAFF) {
$HTMLOUT .= "
    <a class='float-right' href='{$TRINITY20['baseurl']}/staffpanel.php?tool=shistory'> |  | {$lang['index_shoutbox_history']} | </a>";
}
    $HTMLOUT .= " <a class='float-right' href='{$TRINITY20['baseurl']}/shoutbox.php?show_shout=1&amp;show=no'> |  {$lang['index_shoutbox_close']}</a> 
    </div>";
$HTMLOUT .= "<div class='card-section'>";
//$HTMLOUT.= "<div><iframe src='{$TRINITY20['baseurl']}/auto_shout_scroll.php' width='100%' height='30px' frameborder='0' name='auto_shoutbox' marginwidth='0' marginheight='0'></iframe></div>";
$HTMLOUT.= "<form action='shoutbox.php' method='get' target='shoutbox' name='shbox' onsubmit='mysubmit()'>
        <object data='{$TRINITY20['baseurl']}/shoutbox.php' class='shout-table' name='shoutbox'></object>
            <div class='input-group'>
                <div class='input-group-button'>
                    <button class='input-group-button button' type='button' data-toggle='example-dropdown'>{$lang['index_shoutbox_shout']}</button>
                </div>
                <input type='text' class='input-group-field' name='shbox_text' placeholder='Shout Text'>
                <div class='input-group-button'>
                        <input type='submit' class='button' value='{$lang['index_shoutbox_send']}'>
                        <input type='hidden' name='sent' value='yes'>
                </div>
            </div>
            {$commandbutton}{$staffsmiliebutton}{$smilebutton}{$custombutton}{$refreshbutton}
            <a href=\"javascript:SmileIT(':-)','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/smile1.gif' alt='Smile' title='Smile' /></a>
            <a href=\"javascript:SmileIT(':smile:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/smile2.gif' alt='Smiling' title='Smiling' /></a>
            <a href=\"javascript:SmileIT(':-D','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/grin.gif' alt='Grin' title='Grin' /></a>
            <a href=\"javascript:SmileIT(':lol:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/laugh.gif' alt='Laughing' title='Laughing' /></a>
            <a href=\"javascript:SmileIT(':w00t:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/w00t.gif' alt='W00t' title='W00t' /></a>
            <a href=\"javascript:SmileIT(':blum:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/blum.gif' alt='Rasp' title='Rasp' /></a>
            <a href=\"javascript:SmileIT(';-)','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/wink.gif' alt='Wink' title='Wink' /></a>
            <a href=\"javascript:SmileIT(':devil:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/devil.gif' alt='Devil' title='Devil' /></a>
            <a href=\"javascript:SmileIT(':yawn:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/yawn.gif' alt='Yawn' title='Yawn' /></a>
            <a href=\"javascript:SmileIT(':-/','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/confused.gif' alt='Confused' title='Confused' /></a>
            <a href=\"javascript:SmileIT(':o)','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/clown.gif' alt='Clown' title='Clown' /></a>
            <a href=\"javascript:SmileIT(':innocent:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/innocent.gif' alt='Innocent' title='innocent' /></a>
            <a href=\"javascript:SmileIT(':whistle:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/whistle.gif' alt='Whistle' title='Whistle' /></a>
            <a href=\"javascript:SmileIT(':unsure:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/unsure.gif' alt='Unsure' title='Unsure' /></a>
            <a href=\"javascript:SmileIT(':blush:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/blush.gif' alt='Blush' title='Blush' /></a>
            <a href=\"javascript:SmileIT(':hmm:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/hmm.gif' alt='Hmm' title='Hmm' /></a>
            <a href=\"javascript:SmileIT(':hmmm:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/hmmm.gif' alt='Hmmm' title='Hmmm' /></a>
            <a href=\"javascript:SmileIT(':huh:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/huh.gif' alt='Huh' title='Huh' /></a>
            <a href=\"javascript:SmileIT(':look:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/look.gif' alt='Look' title='Look' /></a>
            <a href=\"javascript:SmileIT(':rolleyes:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes' /></a>
            <a href=\"javascript:SmileIT(':kiss:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/kiss.gif' alt='Kiss' title='Kiss' /></a>
            <a href=\"javascript:SmileIT(':blink:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/blink.gif' alt='Blink' title='Blink' /></a>
            <a href=\"javascript:SmileIT(':baby:','shbox','shbox_text')\"><img src='{$TRINITY20['pic_base_url']}smilies/baby.gif' alt='Baby' title='Baby' /></a>
    </form>";
$HTMLOUT .= "</div></div>";
}
if (!($CURUSER['opt1'] & user_options::SHOW_SHOUT)) {
   $HTMLOUT.= "<fieldset><legend><b>{$lang['index_shoutbox']}</b></legend></fieldset><div class='container'><a class='btn btn-default' type='button' href='{$TRINITY20['baseurl']}/shoutbox.php?show_shout=1&amp;show=yes'>{$lang['index_shoutbox_open']}&nbsp;</a></div><hr>";
}
//==end 09 shoutbox
//==End
// End Class
// End File