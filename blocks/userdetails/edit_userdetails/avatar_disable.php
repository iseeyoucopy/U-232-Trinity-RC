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
$HTMLOUT .= "<div class='input-group'>
  <span class='input-group-label'>{$lang['userdetails_avatar_url']}</span>
  <input class='input-group-field'  type='text' name='avatar' value='".htmlsafechars($user["avatar"])."'>
</div>";
if ($user['avatarpos'] != 1) {
    $HTMLOUT .= "<div class='cell medium-12'>
  <fieldset class='fieldset'>
  <legend>{$lang['userdetails_avatarpos']}</legend>
  <input name='avatarpos' value='42' type='radio' id='avatar_pos' aria-describedby='avatar_pos'>
  <label for='avatar_pos'>{$lang['userdetails_remove_shout_d']}</label>";
    if ($user['avatarpos'] == 0) {
        $HTMLOUT .= '<p class="help-text" id="avatar_pos">('.$lang['userdetails_unlimited_d'].')<p>';
    } else {
        $HTMLOUT .= "<p class='help-text' id='avatar_pos'>{$lang['userdetails_until']} ".get_date($user['avatarpos'],
                'DATE')." (".mkprettytime($user['avatarpos'] - TIME_NOW)." {$lang['userdetails_togo']})</p>";
    }
    $HTMLOUT .= "</fieldset></div>";
}
if ($user['avatarpos'] == 1) {
    $HTMLOUT .= '<div class="input-group">
      <span class="input-group-label">'.$lang['userdetails_avatarpos'].'</span>
      <select class="input-group-field" name="avatarpos">
          <option value="0">'.$lang['userdetails_disable_for'].'</option>
          <option value="1">1 '.$lang['userdetails_week'].'</option>
          <option value="2">2 '.$lang['userdetails_weeks'].'</option>
          <option value="4">4 '.$lang['userdetails_weeks'].'</option>
          <option value="8">8 '.$lang['userdetails_weeks'].'</option>
          <option value="90">'.$lang['userdetails_unlimited'].'</option>
      </select>
      <span class="input-group-label">Comment</span>
      <input class="input-group-field" type="text" name="avatardisable_pm">
  </div>';
}
$HTMLOUT .= "<fieldset class='cell medium-4'>
    <legend>{$lang['userdetails_avatar_rights']}<legend>
    <input name='view_offensive_avatar' id='off_avatar_yes' value='yes' type='radio'".($user['view_offensive_avatar'] == "yes" ? " checked='checked'" : "").">
    <label for='off_avatar_yes'>{$lang['userdetails_yes']}</label>
    <input name='view_offensive_avatar' id='off_avatar_no' value='no' type='radio'".($user['view_offensive_avatar'] == "no" ? " checked='checked'" : "").">
    <label for='off_avatar_no'>{$lang['userdetails_no']}</label>
  </fieldset>
  <fieldset class='cell medium-4'>
    <legend>{$lang['userdetails_offensive']}</legend>
    <input name='offensive_avatar' value='yes' type='radio'".($user['offensive_avatar'] == "yes" ? " checked='checked'" : "").">
    <label for='off_avatar_yes'>{$lang['userdetails_yes']}</label>
  <input name='offensive_avatar' value='no' type='radio'".($user['offensive_avatar'] == "no" ? " checked='checked'" : "").">
  <label for='off_avatar_no'>{$lang['userdetails_no']}</label>
  </fieldset>
  <fieldset class='cell medium-4'>
    <legend>{$lang['userdetails_view_offensive']}</legend>
    <input name='avatar_rights' value='yes' type='radio'".($user['avatar_rights'] == "yes" ? " checked='checked'" : "").">
    <label for='off_avatar_yes'>{$lang['userdetails_yes']}</label>
    <input name='avatar_rights' value='no' type='radio'".($user['avatar_rights'] == "no" ? " checked='checked'" : "").">
    <label for='off_avatar_no'>{$lang['userdetails_no']}</label>
  </fieldset>";
