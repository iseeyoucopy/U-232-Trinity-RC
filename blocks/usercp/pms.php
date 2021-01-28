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
$HTMLOUT .= "<input type='hidden' name='action' value='default'>
<div class='card'>
    <div class='card-divider'>{$lang['usercp_pm_opt']}</div>
    <div class='card-section'>
        <div class='grid-x grid-margin-x small-up-2 medium-up-3 large-up-4'>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='notify_pm'><strong>{$lang['usercp_email_notif']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='pmnotif_id'  name='pmnotif' " . (strpos($CURUSER['notifs'], "[pm]") !== false ? "checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='pmnotif_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='notify_pm'>{$lang['usercp_notify_pm']}</p>
                    </div>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='delete_pm'><strong>{$lang['usercp_delete_pms']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='deletepm_id'  name='deletepms'" . ($CURUSER["deletepms"] == "yes" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='deletepm_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='delete_pm'>{$lang['usercp_default_delete']}</p>
                    </div>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='save_pms'><strong>{$lang['usercp_save_pms']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='savepm_id' name='savepms'" . ($CURUSER["savepms"] == "yes" ? " checked='checked'" : "") . ">
                            <label class='switch-paddle' for='savepm_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='save_pms'>{$lang['usercp_default_save']}</p>
                    </div>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='pm_fopm'><strong>{$lang['usercp_pm_fopm']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='checkbox'  id='subscription_id' name='subscription_pm' " . ($CURUSER["subscription_pm"] == "yes" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='subscription_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                    </div>
                    <p class='help-text' id='pm_fopm'>{$lang['usercp_pm_pm01']}</p>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='pm_topm'><strong>{$lang['usercp_pm_topm']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='checkbox'  id='pmondelete_id' name='pm_on_delete' " . ($CURUSER["pm_on_delete"] == "yes" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='pmondelete_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='pm_topm'>{$lang['usercp_pm_pm02']}</p>
                    </div>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='pm_copm'><strong>{$lang['usercp_pm_copm']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='checkbox' id='commentpm_id' name='commentpm' value='yes' " . ($CURUSER["commentpm"] == "yes" ? " checked='checked'" : "") . ">
                            <label class='switch-paddle' for='commentpm_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                    </div>
                    <p class='help-text' id='pm_copm'>{$lang['usercp_pm_pm03']}</p>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='pm_copm'><strong>{$lang['usercp_pm_force']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='checkbox' id='pmforced_id' name='pm_forced' value='yes' " . ($CURUSER["pm_forced"] == "yes" ? " checked='checked'" : "") . ">
                            <label class='switch-paddle' for='pmforced_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                    </div>
                    <p class='help-text' id='pm_copm'>{$lang['usercp_pm_pm04']}</p>
                </div>
            </div> 
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='pm_copm'><strong>{$lang['usercp_accept_pm']}</strong></div>
                    <div class='card-section'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id' name='acceptpms'" . ($CURUSER["acceptpms"] == "yes" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='acceptpm_id'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <strong>{$lang['usercp_only_friends']}</strong>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id1' name='acceptpms'" . ($CURUSER["acceptpms"] == "friends" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='acceptpm_id1'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <strong>{$lang['usercp_only_staff']}</strong>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='switch-input' type='radio' id='acceptpm_id2' name='acceptpms'" . ($CURUSER["acceptpms"] == "no" ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='acceptpm_id2'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='pm_copm'>{$lang['usercp_except_blocks']}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";