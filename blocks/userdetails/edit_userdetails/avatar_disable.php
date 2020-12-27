<?php
        $avatarpos = $user['avatarpos'] != 1;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$avatarpos ? ' rowspan="2"' : '') . ">{$lang['userdetails_avatarpos']}</div>
          <div class='col-sm-2'>" . ($avatarpos ? "<input name='avatarpos' value='42' type='radio'>{$lang['userdetails_remove_avatar_d']}" : $lang['userdetails_no_disablement']) . "</div>";
        if ($avatarpos) {
            if ($user['avatarpos'] == 0) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['avatarpos'], 'DATE') . " (" . mkprettytime($user['avatarpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' name='avatardisable_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="avatarpos">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="avatardisable_pm"></div></div>';
        }
?>