<?php
$HTMLOUT.= '<fieldset><legend>'.$lang['user_b_title3'].'</legend></fieldset>
        <table class="table table-bordered">
        <tr><td><b>'.$lang['user_u_ll1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_login_link" name="userdetails_login_link" value="yes"' . $checkbox_userdetails_login_link . '>
</label><span>'.$lang['user_u_ll2'].'</span></div>   
        </td>
        </tr>
        <tr><td><b>'.$lang['user_u_ftt1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_flush" name="userdetails_flush" value="yes"' . $checkbox_userdetails_flush . '>
</label><span>'.$lang['user_u_ftt2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_join1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_joined" name="userdetails_joined" value="yes"' . $checkbox_userdetails_joined . '>
</label><span>'.$lang['user_u_join2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_onl1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_online_time" name="userdetails_online_time" value="yes"' . $checkbox_userdetails_onlinetime . '>
</label><span>'.$lang['user_u_onl2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_brw1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_browser" name="userdetails_browser" value="yes"' . $checkbox_userdetails_browser . '>
</label><span>'.$lang['user_u_brw2'].'</span></div>        
        </td>
        </tr>   
        '.($INSTALLER09['rep_sys_on'] ? '<tr><td><b>'.$lang['user_u_rep1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_reputation" name="userdetails_reputation" value="yes"' . $checkbox_userdetails_reputation . '>
</label><span>'.$lang['user_u_rep2'].'</span></div>        
        </td>
        </tr>' : '').'       
        <tr><td><b>'.$lang['user_u_phit1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_user_hits" name="userdetails_user_hits" value="yes"' . $checkbox_userdetails_userhits . '>
</label><span>'.$lang['user_u_phit2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_bir1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_birthday" name="userdetails_birthday" value="yes"' . $checkbox_userdetails_birthday . '>
</label><span>'.$lang['user_u_bir2'].'</span></div>       
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_con1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_contact_info" name="userdetails_contact_info" value="yes"' . $checkbox_userdetails_contact_info . '>
</label><span>'.$lang['user_u_con2'].'</span></div>        
       </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_ip1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_iphistory" name="userdetails_iphistory" value="yes"' . $checkbox_userdetails_iphistory . '>
</label><span>'.$lang['user_u_ip2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_ut1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_traffic" name="userdetails_traffic" value="yes"' . $checkbox_userdetails_traffic . '>
</label> <span>'.$lang['user_u_ut2'].'</span></div>       
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_shr1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_share_ratio" name="userdetails_share_ratio" value="yes"' . $checkbox_userdetails_shareratio . '>
</label><span>'.$lang['user_u_shr2'].'</span></div>       
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_st1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_seedtime_ratio" name="userdetails_seedtime_ratio" value="yes"' . $checkbox_userdetails_seedtime_ratio . '>
</label><span>'.$lang['user_u_st2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_seed1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_seedbonus" name="userdetails_seedbonus" value="yes"' . $checkbox_userdetails_seedbonus . '>
</label><span>'.$lang['user_u_seed2'].'</span></div>       
         </td>
        </tr>
        <tr><td><b>'.$lang['user_u_ircs1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_irc_stats" name="userdetails_irc_stats" value="yes"' . $checkbox_userdetails_irc_stats . '>
</label><span>'.$lang['user_u_ircs2'].'</span></div>       
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_cnn1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_connectable_port" name="userdetails_connectable_port" value="yes"' . $checkbox_userdetails_connectable . '>
</label><span>'.$lang['user_u_cnn2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_av1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_avatar" name="userdetails_avatar" value="yes"' . $checkbox_userdetails_avatar . '>
</label><span>'.$lang['user_u_av2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_ucc1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_userclass" name="userdetails_userclass" value="yes"' . $checkbox_userdetails_userclass . '>
</label><span>'.$lang['user_u_ucc2'].'</span></div>       
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_gen1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_gender" name="userdetails_gender" value="yes"' . $checkbox_userdetails_gender . '>
</label><span>'.$lang['user_u_gen2'].'</span></div>       
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_free1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_freestuffs" name="userdetails_freestuffs" value="yes"' . $checkbox_userdetails_freestuffs . '>
</label><span>'.$lang['user_u_free2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_cmm1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_comments" name="userdetails_comments" value="yes"' . $checkbox_userdetails_torrent_comments . '>
</label><span>'.$lang['user_u_cmm2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_fpt1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_forumposts" name="userdetails_forumposts" value="yes"' . $checkbox_userdetails_forumposts . '>
</label><span>'.$lang['user_u_fpt2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_inv1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_invitedby" name="userdetails_invitedby" value="yes"' . $checkbox_userdetails_invitedby . '>
</label><span>'.$lang['user_u_inv2'].'</span></div>        
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_tb1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_torrents_block" name="userdetails_torrents_block" value="yes"' . $checkbox_userdetails_torrents_block . '>
</label><span>'.$lang['user_u_tb2'].'</span></div>       
        </td>
        </tr>   
        <tr><td><b>'.$lang['user_u_sts1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_snatched_staff" name="userdetails_snatched_staff" value="yes"' . $checkbox_userdetails_snatched_staff . '>
</label><span>'.$lang['user_u_sts2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_usinf1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_userinfo" name="userdetails_userinfo" value="yes"' . $checkbox_userdetails_userinfo . '>
</label><span>'.$lang['user_u_usinf2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_pm1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_showpm" name="userdetails_showpm" value="yes"' . $checkbox_userdetails_showpm . '>
</label><span>'.$lang['user_u_pm2'].'</span></div>       
        </td>
        </tr>';
if ($BLOCKS['userdetails_report_user_on']) {		
        $HTMLOUT.= '<tr><td><b>'.$lang['user_u_rpu1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_report_user" name="userdetails_report_user" value="yes"' . $checkbox_userdetails_report . '>
</label><span>'.$lang['user_u_rpu2'].'</span></div>        
        </td>
        </tr>';
}	
$HTMLOUT.= '<tr><td><b>'.$lang['user_u_ust1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_user_status" name="userdetails_user_status" value="yes"' . $checkbox_userdetails_userstatus . '>
</label><span>'.$lang['user_u_ust2'].'</span></div>        
        </td>
        </tr>       
        <tr><td><b>'.$lang['user_u_ucmm1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_user_comments" name="userdetails_user_comments" value="yes"' . $checkbox_userdetails_usercomments . '>
</label><span>'.$lang['user_u_ucmm2'].'</span></div>        
        </td>
        </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>'.$lang['user_u_comp1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_completed" name="userdetails_completed" value="yes"' . $checkbox_userdetails_completed . '>
</label><span>'.$lang['user_u_comp2'].'</span></div>        
        </td>
        </tr>';
}
$HTMLOUT.= '
    <tr><td><b>'.$lang['user_u_sfd1'].'</b></td><td>
        <div class="checkbox-inline"><label><input data-toggle="toggle" type="checkbox" id="userdetails_showfriends" name="userdetails_showfriends" value="yes"' . $checkbox_userdetails_showfriends . '>
</label><span>'.$lang['user_u_sfd2'].'</span></div>        
        </td>
        </tr>
    </table>
<div class="col-sm-offset-5"><input class="btn btn-primary" type="submit" name="submit" value="'.$lang['user_b_butt'].'" tabindex="2" accesskey="s"></div>';
?>