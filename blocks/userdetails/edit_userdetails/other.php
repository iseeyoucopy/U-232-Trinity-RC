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
$HTMLOUT .= "
<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_title']}</span>
    <input class='input-group-field' type='text'name='title' value='".htmlsafechars($user['title'])."'>
</div>
<textarea placeholder='{$lang['userdetails_signature']}' cols='60' rows='2' name='signature'>".htmlsafechars($user['signature'])."</textarea>

<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_signature_rights']}</span>
    <input class='input-group-field' disabled>
    <div class='input-group-button'>
        <div class='switch tiny'>
            <input class='input-group-field switch-input' name='signature_post' type='radio' id='signature_post' ".($user['signature_post'] == "yes" ? " checked='checked'" : "")." value='yes'>
            <label class='switch-paddle' for='signature_post'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    {$lang['userdetails_disable_signature']}
</div>";
if ($CURUSER['class'] == UC_STAFF && $user["class"] > UC_VIP) {
    $HTMLOUT .= "<input type='hidden' name='class' value='{$user['class']}'>";
} else {
    $HTMLOUT .= "<div class='input-group'>
    <span class='input-group-label'>Class</span>
        <select class='input-group-field' name='class'>";
    $maxclass = $CURUSER['class'] == UC_STAFF ? UC_VIP : $CURUSER['class'] - 1;
    for ($i = 0; $i <= $maxclass; ++$i) {
        $HTMLOUT .= "<option value='$i'".($user["class"] == $i ? " selected='selected'" : "").">".get_user_class_name($i)."</option>";
    }
    $HTMLOUT .= "</select></div>";
}
$HTMLOUT .= "<div class='cell large-6'>
    <fieldset class='fieldset'>
        <legend>{$lang['userdetails_supportfor']}</legend>
        <textarea cols='60' rows='2' name='supportfor'>".htmlsafechars($user["supportfor"])."</textarea>
    </fieldset>
    </div>
    <div class='cell large-6'>
    <fieldset class='fieldset'>
        <legend>{$lang['userdetails_support']}</legend>
        <input type='radio' name='support' id='usr_support' value='yes'".($user["support"] == "yes" ? " checked='checked'" : "").">
        <label for='usr_support'>{$lang['userdetails_yes']}</label>
        <input type='radio' name='support' id='usr_support_no' value='no'".($user["support"] == "no" ? " checked='checked'" : "").">
        <label for='usr_support_no'>{$lang['userdetails_no']}</label>
    </fieldset></div>";
if ($CURUSER["class"] < UC_SYSOP) {
    $HTMLOUT .= "<div class='cell large-4 medium-3'>
            <fieldset class='fieldset'>
                <legend>{$lang['userdetails_comment']}</legend>
                <textarea cols='40' rows='6' name='modcomment' readonly='readonly'>".htmlsafechars($user_stats["modcomment"])."</textarea>
            </fieldset>
        </div>";
} else {
    $HTMLOUT .= "<div class='cell large-4 medium-3'>
            <fieldset class='fieldset'>
                <legend>{$lang['userdetails_comment']}</legend>
                <textarea cols='40' rows='6' name='modcomment'>".htmlsafechars($user_stats["modcomment"])."</textarea>
        </fieldset>
        </div>";
}
$HTMLOUT .= "<div class='cell large-4 medium-3'>
        <fieldset class='fieldset'>
            <legend>{$lang['userdetails_add_comment']}</legend>
            <textarea cols='40' rows='6' name='addcomment'></textarea>
        </fieldset>
    </div>
    <div class='cell large-4 medium-3'>
        <fieldset class='fieldset'>
        <legend>{$lang['userdetails_bonus_comment']}</legend>
            <textarea cols='40' rows='6' name='bonuscomment' readonly='readonly' style='background:purple;color:yellow;'>".htmlsafechars($user_stats["bonuscomment"])."</textarea>
        </fieldset>
    </div>
    <fieldset class='cell medium-6'>
        <legend>{$lang['userdetails_enabled']}</legend>
        <input name='enabled' value='yes' type='radio'".($enabled ? " checked='checked'" : "").">{$lang['userdetails_yes']} 
        <input name='enabled' value='no' type='radio'".($enabled ? "" : " checked='checked'").">{$lang['userdetails_no']}
    </fieldset>
    <fieldset class='cell medium-6'>
    <legend>{$lang['userdetails_suspended']}</legend>
        <input name='suspended' value='yes' type='radio'".($user['suspended'] == 'yes' ? ' checked="checked"' : '').">
        {$lang['userdetails_yes']}
        <input name='suspended' value='no' type='radio'".($user['suspended'] == 'no' ? ' checked="checked"' : '').">
        {$lang['userdetails_no']}
        <input placeholder='{$lang['userdetails_suspended_reason']}' type='text' name='suspended_reason'>
    </fieldset>";
