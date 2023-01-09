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
// session so that repeated access of this page cannot happen without the calling script.
//
// You use the create function with the sending script, and the check function with the
// receiving script...
//
// You need to pass the value of $task from the calling script to the receiving script. While
// this may appear dangerous, it still only allows a one shot at the receiving script, which
// effectively stops flooding.
// page verify by retro
class page_verify
{
    function __construct()
    {
        if (session_id() == '') {
            session_start();
        }
    }

    function create($task_name = 'Default')
    {
        global $CURUSER, $TRINITY20, $_SESSION;
        $session_task = $CURUSER['id'] ?? '';
        $_SESSION['Task_Time'] = TIME_NOW;
        $_SESSION['Task'] = md5('user_id:'.$session_task.'::taskname-'.$task_name.'::'.$_SESSION['Task_Time']);
        $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        //$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
    }

    function check($task_name = 'Default')
    {
        global $CURUSER, $TRINITY20, $lang, $_SESSION;
        $returl = (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : $TRINITY20['baseurl']."/login.php");
        $returl = str_replace('&amp;', '&', $returl);
        if (isset($_SESSION['HTTP_USER_AGENT']) && $_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
            stderr("Error", "Please resubmit the form. <a href='".$returl."'>Click HERE</a>", false);
        }
        $session_task_id = $CURUSER['id'] ?? '';
        if (isset($session_task) && $session_task != md5('user_id:'.$session_task_id.'::taskname-'.$task_name.'::'.$_SESSION['Task_Time'])) {
            stderr("Error", "Please resubmit the form. <a href='".$returl."'>Click HERE</a>", false);
        }
        $this->create();
    }
}

?>
