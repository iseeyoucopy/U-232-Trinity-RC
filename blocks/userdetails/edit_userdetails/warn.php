<?php
        $warned = $user['warned'] != 0;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$warned ? ' rowspan="2"' : '') . ">{$lang['userdetails_warned']}</div>
               <div class='col-sm-2'>" . ($warned ? "<input name='warned' value='42' type='radio'>{$lang['userdetails_remove_warned']}" : $lang['userdetails_no_warning']) . "</div>";
        if ($warned) {
            if ($user['warned'] == 1) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['warned'], 'DATE') . " (" . mkprettytime($user['warned'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' name='warned_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">' . $lang['userdetails_warn_for'] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="warned">
        <option value="0">' . $lang['userdetails_warn0'] . '</option>
        <option value="1">' . $lang['userdetails_warn1'] . '</option>
        <option value="2">' . $lang['userdetails_warn2'] . '</option>
        <option value="4">' . $lang['userdetails_warn4'] . '</option>
        <option value="8">' . $lang['userdetails_warn8'] . '</option>
        <option value="90">' . $lang['userdetails_warninf'] . '</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="warned_pm"></div></div>';
        }