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
require_once(INCL_DIR.'user_functions.php');
dbconn(true);
loggedinorreturn();

$lang = array_merge(load_language('global'));
$poll_id = isset($_GET['pollid']) ? (int)$_GET['pollid'] : false;
if (!is_valid_id($poll_id)) {
    stderr('ERROR', 'No poll with that ID');
}
$vote_cast = [];
$_POST['choice'] ??= [];
//-----------------------------------------
// Permissions check
//-----------------------------------------
/*
		if ( ! $CURUSER['can_vote'] )
		{
			stderr( 'ERROR', 'ya nay alood ta vot' );
		}
*/
$query = sql_query("SELECT * FROM polls
                            LEFT JOIN poll_voters ON polls.pid = poll_voters.poll_id
                            AND poll_voters.user_id = {$CURUSER['id']} 
                            WHERE pid = ".sqlesc($poll_id));
if (!$query->num_rows == 1) {
    stderr('ERROR', 'No poll with that ID');
}
$poll_data = $query->fetch_assoc();
if ($poll_data['user_id']) {
    stderr('ERROR', 'You have already voted!');
}
$_POST['nullvote'] ??= 0;
if (!$_POST['nullvote']) {
    if (is_array($_POST['choice']) && count($_POST['choice'])) {
        foreach ($_POST['choice'] as $question_id => $choice_id) {
            if (!$question_id || !isset($choice_id)) {
                continue;
            }
            $vote_cast[$question_id][] = $choice_id;
        }
    }
    foreach (array_keys($_POST) as $k) {
        if (preg_match("#^choice_(\d+)_(\d+)$#", $k, $matches) && $_POST[$k] == 1) {
            $vote_cast[$matches[1]][] = $matches[2];
        }
    }
    $poll_answers = unserialize(stripslashes($poll_data['choices']));
    reset($poll_answers);
    if (count($vote_cast) < (is_countable($poll_answers) ? count($poll_answers) : 0)) {
        stderr('ERROR', 'No vote');
    }
    @sql_query("INSERT INTO poll_voters (user_id, ip_address, poll_id, vote_date)
                        VALUES ({$CURUSER['id']}, ".sqlesc($CURUSER['ip']).",{$poll_data['pid']}, ".TIME_NOW.")");
    $cache->delete($cache_keys['poll_data'].$CURUSER['id']);
    /*
                $update['votes'] = ($poll_data['votes'] + 1);
                $cache->update_row($cache_keys['poll_data'].$CURUSER['id'],  array('votes' => $update['votes']), $TRINITY20['expires']['poll_data']);
    */
    if (-1 == $mysqli->affected_rows) {
        stderr('DBERROR', 'Could not update records');
    }
    foreach ($vote_cast as $question_id => $choice_array) {
        foreach ($choice_array as $choice_id) {
            $poll_answers[$question_id]['votes'][$choice_id]++;
            if ($poll_answers[$question_id]['votes'][$choice_id] < 1) {
                $poll_answers[$question_id]['votes'][$choice_id] = 1;
            }
        }
    }
    $poll_data['choices'] = addslashes(serialize($poll_answers));
    @sql_query("UPDATE polls set votes=votes+1, choices='{$poll_data['choices']}' 
									WHERE pid={$poll_data['pid']}");
    if (-1 == $mysqli->affected_rows) {
        stderr('DBERROR', 'Could not update records');
    }
} else {
    @sql_query("INSERT INTO poll_voters (user_id, ip_address, poll_id, vote_date)
                VALUES({$CURUSER['id']}, ".sqlesc($CURUSER['ip']).", {$poll_data['pid']}, ".TIME_NOW.")");
    $cache->delete($cache_keys['poll_data'].$CURUSER['id']);
    /*
                $update['votes'] = ($poll_data['votes'] + 1);
                $cache->update_row($cache_keys['poll_data'].$CURUSER['id'],  array('votes' => $update['votes']), $TRINITY20['expires']['poll_data']);
    */
    if (-1 == $mysqli->affected_rows) {
        stderr('DBERROR', 'Could not update records');
    }
}
header("location: {$TRINITY20['baseurl']}/index.php");
?>
