<?php
$HTMLOUT .= "<input type='hidden' name='action' value='default'>
<div class='grid-x grid-margin-x small-up-2 medium-up-3 large-up-4'>
    <div class='cell callout'> 
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_notify_pm']}'>
        <strong>{$lang['usercp_email_notif']}</strong>
        </span>
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='pmnotif_id'  name='pmnotif' " . (strpos($CURUSER['notifs'], "[pm]") !== false ? "checked='checked'" : "") . " value='yes'>
            <label class='switch-paddle' for='pmnotif_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_default_delete']}'>
            <strong>{$lang['usercp_delete_pms']}</strong>
        </span>
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='deletepm_id'  name='deletepms'" . ($CURUSER["deletepms"] == "yes" ? " checked='checked'" : "") . " value='yes'>
            <label class='switch-paddle' for='deletepm_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_default_save']}'>
            <strong>{$lang['usercp_save_pms']}</strong>
        </span>
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='savepm_id' name='savepms'" . ($CURUSER["savepms"] == "yes" ? " checked='checked'" : "") . ">
            <label class='switch-paddle' for='savepm_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_pm_pm01']}'>
            <strong>{$lang['usercp_pm_fopm']}</strong>
        </span>      
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='switch-input' type='checkbox'  id='subscription_id' name='subscription_pm' " . ($CURUSER["subscription_pm"] == "yes" ? " checked='checked'" : "") . " value='yes'>
            <label class='switch-paddle' for='subscription_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_pm_pm02']}'>
            <strong>{$lang['usercp_pm_topm']}</strong>
        </span>  
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='switch-input' type='checkbox'  id='pmondelete_id' name='pm_on_delete' " . ($CURUSER["pm_on_delete"] == "yes" ? " checked='checked'" : "") . " value='yes'>
            <label class='switch-paddle' for='pmondelete_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_pm_pm03']}'>
            <strong>{$lang['usercp_pm_copm']}</strong>
        </span> 
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='switch-input' type='checkbox' id='commentpm_id' name='commentpm' value='yes' " . ($CURUSER["commentpm"] == "yes" ? " checked='checked'" : "") . ">
            <label class='switch-paddle' for='commentpm_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div>
    <div class='cell callout'>
        <span data-tooltip class='top' tabindex='2' title='{$lang['usercp_pm_pm04']}'>
            <strong>{$lang['usercp_pm_force']}</strong>
        </span>
        <div class='switch tiny'>
            <input onchange='this.form.submit()' class='switch-input' type='checkbox' id='pmforced_id' name='pm_forced' value='yes' " . ($CURUSER["pm_forced"] == "yes" ? " checked='checked'" : "") . ">
            <label class='switch-paddle' for='pmforced_id'>
                <span class='switch-active' aria-hidden='true'>Yes</span>
                <span class='switch-inactive' aria-hidden='true'>No</span>
            </label>
        </div>
    </div> 
</div>
<div class='callout'>
<span data-tooltip class='top' tabindex='2' title='{$lang['usercp_except_blocks']}'>
    <strong>{$lang['usercp_accept_pm']}</strong>
</span>
<div class='switch tiny'>
    <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id' name='acceptpms'" . ($CURUSER["acceptpms"] == "yes" ? " checked='checked'" : "") . " value='yes'>
    <label class='switch-paddle' for='acceptpm_id'>
        <span class='switch-active' aria-hidden='true'>Yes</span>
        <span class='switch-inactive' aria-hidden='true'>No</span>
    </label>
</div>
<strong>{$lang['usercp_only_friends']}</strong>
<div class='switch tiny'>
    <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id1' name='acceptpms'" . ($CURUSER["acceptpms"] == "friends" ? " checked='checked'" : "") . " value='yes'>
    <label class='switch-paddle' for='acceptpm_id1'>
        <span class='switch-active' aria-hidden='true'>Yes</span>
        <span class='switch-inactive' aria-hidden='true'>No</span>
    </label>
</div>
<strong>{$lang['usercp_only_staff']}</strong>
<div class='switch tiny'>
    <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id2' name='acceptpms'" . ($CURUSER["acceptpms"] == "no" ? " checked='checked'" : "") . " value='yes'>
    <label class='switch-paddle' for='acceptpm_id2'>
        <span class='switch-active' aria-hidden='true'>Yes</span>
        <span class='switch-inactive' aria-hidden='true'>No</span>
    </label>
</div>
</div>";
//$HTMLOUT.= tr($lang['usercp_delete_pms'], "<input onchange='this.form.submit()'  type='checkbox' name='deletepms'" . (($CURUSER['opt1'] & user_options::DELETEPMS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_delete']}", 1);
//$HTMLOUT.= tr($lang['usercp_save_pms'], "<input onchange='this.form.submit()'  type='checkbox' name='savepms'" . (($CURUSER['opt1'] & user_options::SAVEPMS) ? " checked='checked'" : "") . " /> {$lang['usercp_default_save']}", 1);
//$HTMLOUT.= tr("Forum Subscribe Pm", "<input onchange='this.form.submit()'  type='checkbox' name='subscription_pm'" . (($CURUSER['opt1'] & user_options::SUBSCRIPTION_PM) ? " checked='checked'" : "") . " value='yes' />(When someone posts in a subscribed thread, you will be PMed)", 1);
//$HTMLOUT.= tr("Torrent deletion Pm", "<input onchange='this.form.submit()'  type='checkbox' name='pm_on_delete'" . (($CURUSER['opt2'] & user_options_2::PM_ON_DELETE) ? " checked='checked'" : "") . " value='yes' />(When any of your uploaded torrents are deleted, you will be PMed)", 1);
//$HTMLOUT.= tr("Torrent comment Pm", "<input onchange='this.form.submit()'  type='checkbox' name='commentpm'" . (($CURUSER['opt2'] & user_options_2::COMMENTPM) ? " checked='checked'" : "") . " value='yes' />(When any of your uploaded torrents are commented on, you will be PMed)", 1);
