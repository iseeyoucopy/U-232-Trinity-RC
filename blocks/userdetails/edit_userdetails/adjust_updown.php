<?php
        $HTMLOUT.= "<div class='row'>
<div class='col-sm-1'>{$lang['userdetails_addupload']}<img src='{$TRINITY20['pic_base_url']}plus.gif' alt='{$lang['userdetails_change_ratio']}' title='{$lang['userdetails_change_ratio']}!' id='uppic' onclick=\"togglepic('{$TRINITY20['baseurl']}', 'uppic','upchange')\"></div>
<div class='col-sm-2'><input class='form-control' type='text' name='amountup'></div>
        
         <div class='col-sm-2'><select class='form-control'  name='formatup'>
         <option value='mb'>{$lang['userdetails_MB']}</option>
         <option value='gb'>{$lang['userdetails_GB']}</option></select></div>
<div class='col-sm-1'>
<input class='form-control' type='hidden' id='upchange' name='upchange' value='plus'></div>
         
<div class='col-sm-1'>{$lang['userdetails_adddownload']}<img src='{$TRINITY20['pic_base_url']}plus.gif' alt='{$lang['userdetails_change_ratio']}' title='{$lang['userdetails_change_ratio']}!' id='downpic' onclick=\"togglepic('{$TRINITY20['baseurl']}','downpic','downchange')\"></div>
<div class='col-sm-2'><input class='form-control' type='text' name='amountdown'></div>
         
         <div class='col-sm-2'><select class='form-control' name='formatdown'>
         <option value='mb'>{$lang['userdetails_MB']}</option>
         <option value='gb'>{$lang['userdetails_GB']}</option></select></div>

         <div class='col-sm-1'><input class='form-control' type='hidden' id='downchange' name='downchange' value='plus'>
         </div></div>";
?>