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

require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'password_functions.php');
dbconn();
// Begin the session
ini_set('session.use_trans_sid', '0');
session_start();
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global') , load_language('recover'));
$stdhead = array(
    /** include js **/
    'js' => array(
        'jquery',
        'jquery.simpleCaptcha-0.2'
    )
);

if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"] == "reset")
&& !isset($_POST["action"])){
$token = $_GET["key"];
$email = $_GET["email"];
$curDate = date("Y-m-d H:i:s");
$query = sql_query("SELECT * FROM `password_reset_temp` WHERE `key`='".$token."' and `email`='".$email."';");
$fetch = mysqli_fetch_assoc($query) or stderr("{$lang['stderr_errorhead']}", "{$lang['stderr_error8']}");
	$expDate = $fetch['expDate'];
	if ($expDate >= $curDate){
	?>
    <br />
	<form method="post" action="" name="update">
	<input type="hidden" name="action" value="update" />
	<br /><br />
	<label><strong>Enter New Password:</strong></label><br />
	<input type="password" name="pass1" id="pass1" maxlength="15" required />
    <br /><br />
	<label><strong>Re-Enter New Password:</strong></label><br />
	<input type="password" name="pass2" id="pass2" maxlength="15" required/>
    <br /><br />
	<input type="hidden" name="email" value="<?php echo $email;?>"/>
	<input type="submit" id="reset" value="Reset Password" />
	</form>
<?php
}else{
$error .= "<h2>Link Expired</h2>
<p>The link is expired. You are trying to use the expired link which as valid only 24 hours (1 days after request).<br /><br /></p>";
				}
		}
if($error!=""){
	echo "<div class='error'>".$error."</div><br />";
	}			
} // isset email key validate end


if(isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"]=="update")){
$error="";
$pass1 = mysqli_real_escape_string($con,$_POST["pass1"]);
$pass2 = mysqli_real_escape_string($con,$_POST["pass2"]);
$email = $_POST["email"];
$curDate = date("Y-m-d H:i:s");
if ($pass1!=$pass2){
		$error .= "<p>Password do not match, both password should be same.<br /><br /></p>";
		}
	if($error!=""){
		echo "<div class='error'>".$error."</div><br />";
		}else{

$pass1 = make_passhash($pass1);
sql_query('UPDATE users SET passhash=' . sqlesc($newpassword) . ' WHERE id = ' . sqlesc($id));

sql_query("DELETE FROM `password_reset_temp` WHERE `email`='".$email."';");		
	
echo '<div class="error"><p>Congratulations! Your password has been updated successfully.</p>
<p><a href="https://www.allphptricks.com/forgot-password/login.php">Click here</a> to Login.</p></div><br />';
		}		
}
?>