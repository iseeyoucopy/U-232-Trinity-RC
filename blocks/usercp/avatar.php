<?php
   $HTMLOUT.= "
<div class='col-md-8'>	
<table class='table table-bordered'>
	<tr>
	<td><input type='hidden' name='action' value='avatar' />{$lang['usercp_av_opt']}</td>
	</tr>";
    //==Disable avatar selection
    if (!($CURUSER["avatarpos"] == 0 OR $CURUSER["avatarpos"] != 1)) {
        $HTMLOUT.= "<tr><td class='rowhead'>{$lang['usercp_avatar']}</td><td><input name='avatar' size='50' value='" . htmlsafechars($CURUSER["avatar"]) . "' /><br />
    <font class='small'>{$lang['usercp_av_mess1']}\n<br />
    {$lang['usercp_av_mess2']} <a href='{$TRINITY20['baseurl']}/avatar/index.php'>{$lang['usercp_av_mess3']}</a>.<br />
    {$lang['usercp_av_mess4']} <a href='{$TRINITY20['baseurl']}/bitbucket.php'>{$lang['usercp_av_mess5']}</a>.</font>
    </td></tr>";
    } else {
        $HTMLOUT.= "<tr><td class='rowhead'>{$lang['usercp_avatar']}</td><td><input name='avatar' size='50' value='" . htmlsafechars($CURUSER["avatar"]) . "' readonly='readonly'/>
    <br />{$lang['usercp_no_avatar_allow']}</td></tr>";
    }
    //==End
    //=== adding avatar stuff - snuggs :D
    $HTMLOUT.= tr(''.$lang['usercp_av_viewon'].'', '<input type="radio" name="offensive_avatar" '.($CURUSER['offensive_avatar'] == 'yes' ? 'checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes1'].'
     <input type="radio" name="offensive_avatar" '.($CURUSER['offensive_avatar'] == 'no' ? 'checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'', 1);
    //$HTMLOUT.= tr('Is your avatar offensive', '<input class="styled" type="checkbox" name="offensive_avatar"' . (($CURUSER['opt1'] & user_options::OFFENSIVE_AVATAR) ? ' checked="checked"' : '') . ' value="yes" /> (Enable to hide avatar)', 1);
    $HTMLOUT.= tr(''.$lang['usercp_av_viewoff'].'', '<input type="radio" name="view_offensive_avatar" '.($CURUSER['view_offensive_avatar'] == 'yes' ? 'checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes1'].'
     <input type="radio" name="view_offensive_avatar" '.($CURUSER['view_offensive_avatar'] == 'no' ? 'checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'', 1);
    //$HTMLOUT.= tr('View offensive avatars', '<input class="styled" type="checkbox" name="view_offensive_avatar"' . (($CURUSER['opt1'] & user_options::VIEW_OFFENSIVE_AVATAR) ? ' checked="checked"' : '') . ' value="yes" /> (Check to disable viewing of offensive avatars)', 1);
    $HTMLOUT.= tr(''.$lang['usercp_av_viewav'].'', '<input type="radio" name="avatars" '.($CURUSER['avatars'] == 'yes' ? 'checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes2'].'
     <input type="radio" name="avatars" '.($CURUSER['avatars'] == 'no' ? 'checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'', 1);
    //$HTMLOUT.= tr('View avatars', '<input class="styled" type="checkbox" name="avatars"' . (($CURUSER['opt1'] & user_options::AVATARS) ? ' checked="checked"' : '') . ' value="yes" /> (Low bandwidth user may want to disable this)', 1);
    $HTMLOUT.= "<tr><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";

$HTMLOUT.="</table></div>";