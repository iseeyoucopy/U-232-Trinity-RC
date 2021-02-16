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
$HTMLOUT .= "<input type='hidden' name='action' value='security'>
<div class='card'>
    <div class='card-divider'>{$lang['usercp_secu_opt']}</div>
    <div class='card-section'>
        <div class='grid-x grid-margin-x small-up-2 medium-up-3 large-up-4'>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='parked_m'><strong>{$lang['usercp_acc_parked']}</strong></div>
                    <div class='card-section float-center'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='parked' name='parked'" . ((($CURUSER['opt1'] & user_options::PARKED) !== 0) ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='parked'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='parked_m'>{$lang['usercp_acc_parked_message']}</p>
                        <p class='help-text' id='parked_m'>{$lang['usercp_acc_parked_message1']}</p>
                    </div>
                </div>
            </div>";
            if ($CURUSER['anonymous_until'] == '0'){
            $HTMLOUT.= "<div class='cell'>
                    <div class='card'>
                    <div class='card-divider' aria-describedby='anonymous_m'><strong>{$lang['usercp_default_anonymous']}</strong></div>
                    <div class='card-section float-center'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='anonymous' name='anonymous'" . ((($CURUSER['opt1'] & user_options::ANONYMOUS) !== 0) ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='anonymous'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>";
            }
            $HTMLOUT.= "<div class='cell'>
                <div class='card'>
                    <div class='card-divider' aria-describedby='show_email_m'><strong>{$lang['usercp_email_shw']}</strong></div>
                    <div class='card-section float-center'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='show_email' name='show_email'" . ((($CURUSER['opt1'] & user_options::SHOW_EMAIL) !== 0) ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='show_email'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                        <p class='help-text' id='show_email_m'>{$lang['usercp_email_visi']}</p>
                    </div>
                </div>
            </div>
            <div class='cell'>
                <div class='card'>
                    <div class='card-divider'><strong>{$lang['usercp_secu_curr']}</strong></div>
                    <div class='card-section float-center'>
                        <div class='switch large'>
                            <input onchange='this.form.submit()' class='input-group-field switch-input' type='checkbox' id='hide_cur' name='hidecur'" . ((($CURUSER['opt1'] & user_options::HIDECUR) !== 0) ? " checked='checked'" : "") . " value='yes'>
                            <label class='switch-paddle' for='hide_cur'>
                                <span class='switch-active' aria-hidden='true'>Yes</span>
                                <span class='switch-inactive' aria-hidden='true'>No</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='card'>
            <div class='card-divider'><strong>{$lang['usercp_chpass']}</strong></div>
            <div class='card-section float-center'>
                <div class='input-group'>
                    <span class='input-group-label'><i class='fas fa-unlock-alt'></i></span>
                    <input class='input-group-field' placeholder='Type password' type='password' size='50'  name='chpassword' autocomplete='new-password'>
                    <span class='input-group-label'><i class='fas fa-unlock-alt'></i></span>
                    <input class='input-group-field' placeholder='{$lang['usercp_pass_again']}' type='password' size='50' name='passagain'>
                    <div class='input-group-button'>
                        <input class='button' type='submit' value='Change Password'>
                    </div>
                </div>
            </div>
        </div>
        <div class='card'>
            <div class='card-divider'  aria-describedby='ch_email'><strong>{$lang['usercp_email']}</strong></div>
            <div class='card-section float-center'>
                <div class='input-group'>
                    <span class='input-group-label'><i class='fas fa-at'></i></i></span>
                    <input class='input-group-field' type='text' size='50' name='email' value='" . htmlsafechars($CURUSER["email"]) . "'>
                    <span class='input-group-label'><i class='fas fa-unlock-alt'></i></span>
                    <input class='input-group-field' placeholder='Please type your password' type='password' name='chmailpass' size='50'>
                    <div class='input-group-button'>
                        <input class='button' type='submit' value='Change Email'>
                    </div>
                </div>
                <p class='help-text' id='ch_email'>{$lang['usercp_note']}</p>
            </div>
        </div>";
        $secretqs = "<option value='0'>{$lang['usercp_none_select']}</option>";
        $questions = array(
            array(
                "id" => "1",
                "question" => "{$lang['usercp_q1']}"
            ) ,
            array(
                "id" => "2",
                "question" => "{$lang['usercp_q2']}"
            ) ,
            array(
                "id" => "3",
                "question" => "{$lang['usercp_q3']}"
            ) ,
            array(
                "id" => "4",
                "question" => "{$lang['usercp_q4']}"
            ) ,
            array(
                "id" => "5",
                "question" => "{$lang['usercp_q5']}"
            ) ,
            array(
                "id" => "6",
                "question" => "{$lang['usercp_q6']}"
            )
        );
        foreach ($questions as $sctq) {
            $secretqs.= "<option value='" . $sctq['id'] . "'" . ($CURUSER["passhint"] == $sctq['id'] ? " selected='selected'" : "") . ">" . $sctq['question'] . "</option>";
        }
        $HTMLOUT.= "<div class='card'>
            <div class='card-divider'><strong>Secret</strong></div>
            <div class='card-section float-center'>
                <div class='input-group'>
                    <span class='input-group-label'>{$lang['usercp_question']}</span>
                    <select class='input-group-field' name='changeq'>$secretqs</select>
                    <span class='input-group-label'>{$lang['usercp_sec_answer']}</span>
                    <input class='input-group-field' type='text' name='secretanswer' size='50'>
                    <div class='input-group-button'>
                        <input class='button' type='submit' value='Change Secret'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>";
