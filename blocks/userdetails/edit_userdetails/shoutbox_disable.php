<?php
        $chatpost = $user['chatpost'] != 1;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$chatpost ? ' rowspan="2"' : '') . ">{$lang['userdetails_chatpos']}</div>
               <div class='col-sm-2'>" . ($chatpost ? "<input name='chatpost' value='42' type='radio'>{$lang['userdetails_remove_shout_d']}" : $lang['userdetails_no_disablement']) . "</div>";
        if ($chatpost) {
            if ($user['chatpost'] == 0) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['chatpost'], 'DATE') . " (" . mkprettytime($user['chatpost'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' name='chatdisable_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="chatpost">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="chatdisable_pm"></div></div>';
        }