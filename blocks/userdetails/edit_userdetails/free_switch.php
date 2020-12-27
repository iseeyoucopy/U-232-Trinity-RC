<?php
        $free_switch = $user['free_switch'] != 0;
        $HTMLOUT.= "<div class='col-sm-1 text-right'" . (!$free_switch ? '' : '') . ">{$lang['userdetails_freeleech_status']}</div>
                <div class='col-sm-1'>" . ($free_switch ? "<input name='free_switch' value='42' type='radio'>{$lang['userdetails_remove_freeleech']}" :'') . "</div>";
        if ($free_switch) {
            if ($user['free_switch'] == 1) $HTMLOUT.= '<div class="col-sm-2">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['free_switch'], 'DATE') . " (" . mkprettytime($user['free_switch'] - TIME_NOW) . " {$lang['userdetails_togo']})</div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_freeleech_for'].' <select class="form-control" name="free_switch">
         <option value="0">------</option>
         <option value="1">1 '.$lang['userdetails_week'].'</option>
         <option value="2">2 '.$lang['userdetails_weeks'].'</option>
         <option value="4">4 '.$lang['userdetails_weeks'].'</option>
         <option value="8">8 '.$lang['userdetails_weeks'].'</option>
         <option value="90">'.$lang['userdetails_unlimited'].'</option>
         </select></div>
         <div class="col-sm-2">'.$lang['userdetails_pm_comment'].':<input class="form-control" type="text" name="free_pm"></div></div>';
        }