<?php
$HTMLOUT.='<fieldset><legend>'.$lang['user_b_title2'].'</legend></fieldset>
        <table class="table table-bordered">        
    <tr class="userblock"><td><b>'.$lang['user_s_free1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle"  type="checkbox" id="stdhead_freeleech" name="stdhead_freeleech" value="yes"' . $checkbox_global_freeleech . '>
</label><span>'.$lang['user_s_free2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_s_rep1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_reports" name="stdhead_reports" value="yes"' . $checkbox_global_staff_report . '>
</label><span>'.$lang['user_s_rep2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_s_app1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_uploadapp" name="stdhead_uploadapp" value="yes"' . $checkbox_global_staff_uploadapp . '>
</label><span>'.$lang['user_s_app1'].'</span></div>        
        </td>
        </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '        
    <tr><td><b>'.$lang['user_s_dem1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_demotion" name="stdhead_demotion" value="yes"' . $checkbox_global_demotion . '>
</label><span>'.$lang['user_s_dem2'].'</span></div>        
        </td>
        </tr>';
}
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>'.$lang['user_s_warn1'].'</b></td><td>
        <div class="checkbox-inline"><label><input type="checkbox" id="stdhead_staff_message" name="stdhead_staff_message" value="yes"' . $checkbox_global_staff_message_alert . '>
</label><span>'.$lang['user_s_warn2'].'</span></div>        
        </td>
        </tr>';
}
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>'.$lang['user_s_bug1'].'</b></td><td>
         <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_bugmessage" name="stdhead_bugmessage" value="yes"' . $checkbox_global_bugmessage . '>
</label><span>'.$lang['user_s_bug2'].'</span></div>        
        </td>
        </tr>';
}
$HTMLOUT.= '
    <tr><td><b>'.$lang['user_s_mess1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_newpm" name="stdhead_newpm" value="yes"' . $checkbox_global_message_alert . '>
</label><span>'.$lang['user_s_mess2'].'</span></div>        
       </td>
        </tr>       
        <tr><td><b>'.$lang['user_s_hh1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_happyhour" name="stdhead_happyhour" value="yes"' . $checkbox_global_happyhour . '>
</label><span>'.$lang['user_s_hh2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_s_ch1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_crazyhour" name="stdhead_crazyhour" value="yes"' . $checkbox_global_crazyhour . '>
</label><span>'.$lang['user_s_ch2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_s_kc1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_freeleech_contribution" name="stdhead_freeleech_contribution" value="yes"' . $checkbox_global_freeleech_contribution . '>
</label><span>'.$lang['user_s_kc2'].'</span></div>        
        </td>
       </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
$HTMLOUT.= '
        <tr><td><b>'.$lang['user_s_stq1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="stdhead_stafftools" name="stdhead_stafftools" value="yes"' . $checkbox_global_stafftools . '>
</label><span>'.$lang['user_s_stq2'].'</span></div>        
        </td>
        </tr>';
}
       $HTMLOUT.= '</table><div class="col-sm-offset-5"><input class="btn btn-primary" type="submit" name="submit" value="'.$lang['user_b_butt'].'" tabindex="2" accesskey="s"></div><br><br>';
?>