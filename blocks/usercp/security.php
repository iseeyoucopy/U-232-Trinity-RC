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
$HTMLOUT.= "
<div class='col-md-10'>
	<table class='table table-bordered'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='security' />{$lang['usercp_secu_opt']}</td></tr>";
    if (get_parked() == '1') 
        $HTMLOUT.= tr($lang['usercp_acc_parked'], "<input type='radio' name='parked'".($CURUSER["parked"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']."
    <input type='radio' name='parked'".($CURUSER["parked"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."
    <br /><font class='small' size='1'>{$lang['usercp_acc_parked_message']}<br />{$lang['usercp_acc_parked_message1']}</font>", 1);
    $HTMLOUT.= tr($lang['usercp_acc_parked'], "<input type='checkbox' name='parked'" . (($CURUSER['opt1'] & user_options::PARKED) ? " checked='checked'" : "") . " value='yes' />
    <br /><font class='small' size='1'>{$lang['usercp_acc_parked_message']}<br />{$lang['usercp_acc_parked_message1']}</font>", 1);
    if (get_anonymous() != '0') $HTMLOUT.= tr($lang['usercp_anonymous'], "<input type='checkbox' name='anonymous'".($CURUSER["anonymous"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_default_anonymous']}", 1);
     $HTMLOUT.= tr("{$lang['usercp_secu_curr']}", "<input type='radio' name='hidecur'".($CURUSER["hidecur"] == "yes" ? " checked='checked'" : "")." value='yes' />".$lang['usercp_av_yes1']."<input type='radio' name='hidecur'".($CURUSER["hidecur"] == "no" ? " checked='checked'" : "")." value='no' />".$lang['usercp_av_no1']."", 1);
    $HTMLOUT.= tr($lang['usercp_anonymous'], "<input type='checkbox' name='anonymous'" . (($CURUSER['opt1'] & user_options::ANONYMOUS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_anonymous']}", 1);
    $HTMLOUT.= tr("Hide current seed and leech", "<input type='checkbox' name='hidecur'" . (($CURUSER['opt1'] & user_options::HIDECUR) ? " checked='checked'" : "") . " value='yes' />(Hide your snatch lists)", 1);
    $HTMLOUT.= tr($lang['usercp_email'], "<input type='text' name='email' size='50' value='" . htmlsafechars($CURUSER["email"]) . "' /><br />{$lang['usercp_email_pass']}<br /><input type='password' name='chmailpass' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $HTMLOUT.= "<tr><td colspan='2' align='left'>{$lang['usercp_note']}</td></tr>\n";
    //=== email forum stuff
    $HTMLOUT.= tr($lang['usercp_email_shw'], '<input type="radio" name="show_email" '.($CURUSER['show_email'] == 'yes' ? ' checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes1'].'
    <input type="radio" name="show_email" '.($CURUSER['show_email'] == 'no' ? ' checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'<br />
    '.$lang['usercp_email_visi'].'', 1);
    /*$HTMLOUT.= tr('Show Email', '<input class="styled" type="checkbox" name="show_email"' . (($CURUSER['opt1'] & user_options::SHOW_EMAIL) ? ' checked="checked"' : '') . ' value="yes" /> Yes<br />
	  Do you wish to have your email address visible on the forums?', 1);*/
    $HTMLOUT.= tr($lang['usercp_chpass'], "<input type='password' name='chpassword' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $HTMLOUT.= tr($lang['usercp_pass_again'], "<input type='password' name='passagain' size='50' class='keyboardInput' onkeypress='showkwmessage();return false;' />", 1);
    $secretqs = "<option value='0'>{$lang['usercp_none_select']}</option>\n";
    $questions = array(
        array(
            "id" => "1",
            "question" => "{$lang['usercp_q1']}"
        ) ,
        array(
            "id" => "2",
            "question" => "{$lang['usercp_q2']}"
        ) ,
        array(
            "id" => "3",
            "question" => "{$lang['usercp_q3']}"
        ) ,
        array(
            "id" => "4",
            "question" => "{$lang['usercp_q4']}"
        ) ,
        array(
            "id" => "5",
            "question" => "{$lang['usercp_q5']}"
        ) ,
        array(
            "id" => "6",
            "question" => "{$lang['usercp_q6']}"
        )
    );
    foreach ($questions as $sctq) {
        $secretqs.= "<option value='" . $sctq['id'] . "'" . ($CURUSER["passhint"] == $sctq['id'] ? " selected='selected'" : "") . ">" . $sctq['question'] . "</option>\n";
    }
    $HTMLOUT.= tr($lang['usercp_question'], "<select name='changeq'>\n$secretqs\n</select>", 1);
    $HTMLOUT.= tr($lang['usercp_sec_answer'], "<input type='text' name='secretanswer' size='40' />", 1);
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</table></div>";