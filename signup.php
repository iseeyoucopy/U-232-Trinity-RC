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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(CLASS_DIR.'page_verify.php');
require_once(CACHE_DIR.'timezones.php');
dbconn();
global $CURUSER;
if (!$CURUSER) {
    get_template();
} else {
    header("Location: {$TRINITY20['baseurl']}/index.php");
    exit();
}
ini_set('session.use_trans_sid', '0');
if ($TRINITY20['captcha_on'] === true) {
    $stdfoot = [
        /** include js **/
        'js' => [
            'check',
            'jquery.pstrength-min.1.2',
            'jquery.simpleCaptcha-0.2',
        ],
    ];
} else {
    $stdfoot = [
        /** include js **/
        'js' => [
            'check',
            'jquery.pstrength-min.1.2',
        ],
    ];
}
$lang = array_merge(load_language('global'), load_language('signup'));
if (!$TRINITY20['openreg']) {
    stderr($lang['stderr_errorhead'],
        "{$lang['signup_inviteonly']}<a href='".$TRINITY20['baseurl']."/invite_signup.php'><b>&nbsp;{$lang['signup_here']}</b></a>");
}
$HTMLOUT = $year = $month = $day = $gender = '';
$HTMLOUT .= "
    <script type='text/javascript'>
    /*<![CDATA[*/
    $(function() {
    $('.password').pstrength();
    });
    /*]]>*/
    </script>";
$newpage = new page_verify();
$newpage->create('tesu');
if (get_row_count('users') >= $TRINITY20['maxusers']) {
    stderr($lang['stderr_errorhead'], sprintf($lang['stderr_ulimit'], $TRINITY20['maxusers']));
}
//==timezone select
$offset = (string)$TRINITY20['time_offset'];
$time_select = "<select class='input-group-field' name='user_timezone'>";
foreach ($TZ as $off => $words) {
    if (preg_match("/^time_(-?[\d\.]+)$/", $off, $match)) {
        $time_select .= $match[1] == $offset ? "<option value='{$match[1]}' selected='selected'>$words</option>\n" : "<option value='{$match[1]}'>$words</option>\n";
    }
}
$time_select .= "</select>";
//==country by pdq
function countries()
{
    global $cache, $TRINITY20;
    if (($ret = $cache->get('countries::arr')) === false) {
        ($res = sql_query("SELECT id, name, flagpic FROM countries ORDER BY name ASC")) || sqlerr(__FILE__, __LINE__);
        while ($row = $res->fetch_assoc()) {
            $ret = (array)$ret;
            $ret[] = $row;
        }
        $cache->set('countries::arr', $ret, $TRINITY20['expires']['user_flag']);
    }
    return $ret;
}

$country = '';
$countries = countries();
$user_country = isset($CURUSER['country']) ? "{$CURUSER['country']}" : '';
foreach ($countries as $cntry) {
    $country .= "<option value='".(int)$cntry['id']."'".($user_country == $cntry['id'] ? " selected='selected'" : "").">".htmlsafechars($cntry['name'])."</option>\n";
}
$gender .= "<select class='input-group-field' name='gender'>
    <option value='Male'>{$lang['signup_male']}</option>
    <option value='Female'>{$lang['signup_female']}</option>
    <option value='NA'>{$lang['signup_na']}</option>
    </select>";
// Normal Entry Point...

//==09 Birthday mod
$year .= "<select class='input-group-field' id='sel1' name='year'>";
$year .= "<option value='0000'>{$lang['signup_year']}</option>";
$i = "2020";
while ($i >= 1920) {
    $year .= "<option value='".$i."'>".$i."</option>";
    $i--;
}
$year .= "</select>";
$month .= "<select class='input-group-field' id='sel2' name='month'>
    <option value='00'>{$lang['signup_month']}</option>
    <option value='01'>{$lang['signup_jan']}</option>
    <option value='02'>{$lang['signup_feb']}</option>
    <option value='03'>{$lang['signup_mar']}</option>
    <option value='04'>{$lang['signup_apr']}</option>
    <option value='05'>{$lang['signup_may']}</option>
    <option value='06'>{$lang['signup_jun']}</option>
    <option value='07'>{$lang['signup_jul']}</option>
    <option value='08'>{$lang['signup_aug']}</option>
    <option value='09'>{$lang['signup_sep']}</option>
    <option value='10'>{$lang['signup_oct']}</option>
    <option value='11'>{$lang['signup_nov']}</option>
    <option value='12'>{$lang['signup_dec']}</option>
    </select>";
$day .= "<select class='input-group-field' id='sel3' name='day'>";
$day .= "<option value='00'>{$lang['signup_day']}</option>";
$i = 1;
while ($i <= 31) {
    if ($i < 10) {
        $day .= "<option value='0".$i."'>0".$i."</option>";
    } else {
        $day .= "<option value='".$i."'>".$i."</option>";
    }
    $i++;
}
$day .= "</select>";
//==End Birthday
//==Passhint
$passhint = "";
$questions = [
    [
        "id" => "1",
        "question" => "{$lang['signup_q1']}",
    ],
    [
        "id" => "2",
        "question" => "{$lang['signup_q2']}",
    ],
    [
        "id" => "3",
        "question" => "{$lang['signup_q3']}",
    ],
    [
        "id" => "4",
        "question" => "{$lang['signup_q4']}",
    ],
    [
        "id" => "5",
        "question" => "{$lang['signup_q5']}",
    ],
    [
        "id" => "6",
        "question" => "{$lang['signup_q6']}",
    ],
];
foreach ($questions as $sph) {
    $passhint .= "<option value='".$sph['id']."'>".$sph['question']."</option>\n";
}
//==End Passhint
$HTMLOUT .= "".($TRINITY20['captcha_on'] ? "<script type='text/javascript'>
	  /*<![CDATA[*/
	  $(document).ready(function () {
	  $('#captchasignup').simpleCaptcha();
    });
    /*]]>*/
    </script>" : "")."
<div class='grid-container'>
	<div class='grid-x grid-padding-x align-center-middle text-center margin-top-2'>
	<div class='callout'>
    <form role='form' method='post' title='signup' action='takesignup.php'>
		<div class='input-group'>
			<div id='namecheck'></div>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fa fa-user'></i>
			</span>
			<input class='input-group-field' type='text' placeholder='{$lang['signup_uname']}' name='wantusername' id='wantusername' onblur='checkit();'>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fas fa-lock'></i> 
			</span>
			<input class='input-group-field' type='password' placeholder='{$lang['signup_pass']}' name='wantpassword'>
			<span class='input-group-label'>
				<i class='fas fa-lock'></i> 
			</span>
			<input class='input-group-field' type='password' placeholder='{$lang['signup_passa']}' name='passagain'>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fas fa-barcode'></i>
			</span>
			<input class='input-group-field' type='text' placeholder='Choose a 4 digit Pin Code' name='pin_code'>
			<span class='input-group-label'>
				<i class='fas fa-barcode'></i>
			</span>
			<input class='input-group-field' type='text' placeholder='Repeat Pin Code' name='pin_code2'>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fas fa-envelope'></i>
			</span>
			<input class='input-group-field' type='text' placeholder='{$lang['signup_email']}' name='email'>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fas fa-user-clock'></i>
			</span>
			{$time_select}
		</div>
			<div class='input-group'>
					<span class='input-group-label'>
						<i class='fas fa-birthday-cake'></i>
					</span>
			".$year.$month.$day."
		</div>
		<label for='answer-question'>{$lang['signup_select']}{$lang['signup_this_answer']}{$lang['signup_this_answer1']}</label>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='fas fa-question'></i>
			</span>
			<select class='input-group-field' id='answer-question' name='passhint'>\n$passhint\n</select>
			<span class='input-group-label'><i class='fas fa-align-right'></i></span>
				<input class='input-group-field' type='text' placeholder='{$lang['signup_hint_here']}' name='hintanswer'>
		</div>
		<div class='input-group'>
			<span class='input-group-label'>
				<i class='far fa-flag'></i>
			</span>
			<select class='input-group-field' name='country'>\n$country\n</select>
			<span class='input-group-label'>
				<i class='fas fa-venus-mars'></i>
			</span>
			$gender
		</div>
		<div class='callout warning'>
			<input type='checkbox' name='rulesverify' value='yes' id='rulescheck'>
			<label for='rulescheck'>{$lang['signup_rules']}</label>
			<input type='checkbox' name='faqverify' value='yes' id='faqcheck'>
			<label for='faqcheck'>{$lang['signup_faq']}</label>
			<input type='checkbox' name='ageverify' value='yes' id='agecheck'>
			<label for='agecheck' >{$lang['signup_age']}</label>
		</div>
	".($TRINITY20['captcha_on'] ? "<div class='form-group text-center'><div id='captchasignup'></div></div>" : "")."
	<div class='form-group'>
		<span><input name='submitme' type='submit' value='Register' class='button expanded'></span></div>";
$HTMLOUT .= "</form></div></div></div>";
echo stdhead($lang['head_signup']).$HTMLOUT.stdfoot($stdfoot);
?>
