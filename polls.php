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
function parse_poll()
{
    global $CURUSER, $TRINITY20, $cache, $cache_keys;
    $htmlout = "";
    $check = 0;
    $poll_footer = "";
    $GVARS = [
        'allow_creator_vote' => 1,
        'allow_result_view' => 1,
        'allow_poll_tags' => 1,
    ]; // move this elsewhere later!
    if (($poll_data = $cache->get($cache_keys['poll_data'].$CURUSER['id'])) === false) {
        //$poll_data = array();
        //search for a poll with given ID
        $query = sql_query("SELECT * FROM polls
                            LEFT JOIN poll_voters ON polls.pid = poll_voters.poll_id
                            AND poll_voters.user_id = ".sqlesc($CURUSER['id'])." 
                            ORDER BY polls.start_date DESC
                            LIMIT 1");
        //Did we find the poll?
        if (!$query->num_rows) {
            return "Nothing to vote on right now!";
        }
        while ($row = $query->fetch_assoc()) {
            $poll_data = $row;
        }
        $cache->set($cache_keys['poll_data'].$CURUSER['id'], $poll_data, $TRINITY20['expires']['poll_data']);
    }
    //return $poll_data;
    $member_voted = 0;
    $total_votes = 0;
    //Has they ever posticated before?
    if ($poll_data['user_id']) {
        $member_voted = 1;
        //return "true";
    }
    // Make sure they can't post again
    if ($member_voted) {
        $check = 1;
        $poll_footer = 'You have already voted';
    }
    //Does we want the creator to vote on their own poll?
    if ($poll_data['starter_id'] == $CURUSER['id'] && $GVARS['allow_creator_vote'] !== 1) {
        $check = 1;
        $poll_footer = 'poll_you_created';
    }
    //The following can be setup for guest ie; no loggedinorreturn() on index
    /*
        if ( ! $CURUSER['id'] ) //$poll_data['user_id'] )
        {
         if ( !$GVARS['allow_result_view'] )
         {
          $check 		= 2;
         }
         else
         {
        		$check      = 1;
          }
    		 return $check.$poll_footer;
        	$poll_footer = 'Guests can\'t view polls!';
        }
    */
    //allow viewing of poll results before voting?
    if ($GVARS['allow_result_view'] === 1 && (isset($_GET['mode']) && $_GET['mode'] == 'show')) {
        $check = 1;
        $poll_footer = "";
    }
    if ($check == 1) {
        //ok, lets get this show on the road!
        $htmlout = poll_header($poll_data['pid'], htmlspecialchars($poll_data['poll_question'], ENT_QUOTES));
        $poll_answers = unserialize(stripslashes($poll_data['choices']));
        reset($poll_answers);
        foreach ($poll_answers as $id => $data) {
            //subtitle question
            $question = htmlspecialchars($data['question'], ENT_QUOTES);
            $choice_html = "";
            $tv_poll = 0;
            //get total votes for each choice
            foreach ($poll_answers[$id]['votes'] as $number) {
                $tv_poll += (int)$number;
            }
            // Get the choises from the unserialised array
            foreach ($data['choice'] as $choice_id => $text) {
                $choice = htmlspecialchars($text, ENT_QUOTES);
                $votes = (int)$data['votes'][$choice_id];
                if (strlen($choice) < 1) {
                    continue;
                }
                if ($GVARS['allow_poll_tags'] !== 0) {
                    $choice = preg_replace("/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i", "<a href=\"\\1\">\\2</a>", $choice);
                }
                $percent = $votes == 0 ? 0 : $votes / $tv_poll * 100;
                $percent = sprintf('%.2f', $percent);
                $width = $percent > 0 ? (int)($percent * 2) : 0;
                $choice_html .= poll_show_rendered_choice($choice_id, $votes, $id, $choice, $percent, $width);
            }
            $htmlout .= poll_show_rendered_question($id, $question, $choice_html);
        }
        $htmlout .= show_total_votes($tv_poll);
    } elseif ($check == 2) {
        // only for guests when view before vote is off
        $htmlout = poll_header($poll_data['pid'], htmlspecialchars($poll_data['poll_question'], ENT_QUOTES));
        //$htmlout.= poll_show_no_guest_view();
        $htmlout .= show_total_votes($total_votes);
    } else {
        $poll_answers = unserialize(stripslashes($poll_data['choices']));
        reset($poll_answers);
        //output poll form
        $htmlout = poll_header($poll_data['pid'], htmlspecialchars($poll_data['poll_question'], ENT_QUOTES));
        foreach ($poll_answers as $id => $data) {
            // get the question again!
            $question = htmlspecialchars($data['question'], ENT_QUOTES);
            $choice_html = "";
            // get choices for this question
            foreach ($data['choice'] as $choice_id => $text) {
                $choice = htmlspecialchars($text, ENT_QUOTES);
                $votes = (int)$data['votes'][$choice_id];
                if (strlen($choice) < 1) {
                    continue;
                }
                //do we wanna allow URL's and if so convert them
                if ($GVARS['allow_poll_tags'] !== 0) {
                    $choice = $s = preg_replace("/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i", "<a href=\"\\1\">\\2</a>", $choice);
                }
                if (isset($data['multi']) && $data['multi'] == 1) {
                    $choice_html .= poll_show_form_choice_multi($choice_id, $votes, $id, $choice);
                } else {
                    $choice_html .= poll_show_form_choice($choice_id, $votes, $id, $choice);
                }
            }
            $choice_html = "<table style='cellpadding=4 cellspacing=0'>{$choice_html}</table>";
            $htmlout .= poll_show_form_question($id, $question, $choice_html);
        }
        $htmlout .= show_total_votes($total_votes);
    }
    $htmlout .= poll_footer();
    if ($poll_footer != "") {
        $htmlout = str_replace("<!--VOTE-->", $poll_footer, $htmlout);
    } elseif ($GVARS['allow_result_view'] === 1) {
        if (isset($_GET['mode']) && $_GET['mode'] == 'show') {
            $htmlout = str_replace("<!--SHOW-->", button_show_voteable(), $htmlout);
        } else {
            $htmlout = str_replace(["<!--SHOW-->", "<!--VOTE-->"], [button_show_results(), button_vote()], $htmlout);
        }
    } else {
        //this section not for reviewing votes!
        $htmlout = str_replace(["<!--VOTE-->", "<!--SHOW-->"], [button_vote(), button_null_vote()], $htmlout);
    }
    return $htmlout;
}

///////////////////////////////////////////////
///////////////////////////////////////////////
//AUX FUNCTIONS
///////////////////////////////////////////////
///////////////////////////////////////////////
function poll_header($pid = "", $poll_q = "")
{
    global $TRINITY20;
    return "<script type=\"text/javascript\">
    /*<![CDATA[*/
    function go_gadget_show()
    {
      window.location = \"{$TRINITY20['baseurl']}/index.php?pollid={$pid}&mode=show&st=main\";
    }
    function go_gadget_vote()
    {
      window.location = \"{$TRINITY20['baseurl']}/index.php?pollid={$pid}&st=main\";
    }
    /*]]>*/
    </script>
    <form action='{$TRINITY20['baseurl']}/polls_take_vote.php?pollid={$pid}&amp;st=main&amp;addpoll=1' method='post'>
    

";
}

function poll_footer()
{
    return "<span><!--VOTE-->&nbsp;<!--SHOW--></span>
          <span><!-- no content --></span>

    </form>";
}

function poll_show_rendered_choice($choice_id = "", $votes = "", $id = "", $answer = "", $percentage = "", $width = "")
{
    global $TRINITY20;
    /*$htmlout= "<table style='cellpadding=4 cellspacing=0'><tr>
      <td style='width=25% colspan=2'>$answer</td>
      <td style='width=10% nowrap'> [ <b>$votes</b> ] </td>
      <td style='width=70% nowrap'>
      <img src='{$TRINITY20['pic_base_url']}polls/bar.gif' width='$width' height='11' style='vertical-align:center' alt='' />
      &nbsp;[$percentage%]
      </td>
      </tr></table>";
       return $htmlout;
      */
    $htmlout = "<label>$answer&nbsp;[Votes:&nbsp;$votes]</label>
    <div class='progress' role='progressbar' tabindex='0' style='background-color:transparent;' aria-valuenow='$percentage%' aria-valuemin='0' aria-valuemax='100'>
    <span class='progress-meter' style='width:$percentage%'>
    <span class='progress-meter-text'>$percentage%</span></span>
    </div>";
    return $htmlout;
}

function poll_show_rendered_question($id = "", $question = "", $choice_html = "")
{
    return "
     <div class='text-left'><strong>{$question}</strong></div>
     $choice_html";
}

function show_total_votes($total_votes = "")
{
    return "<div class='text-left'><b>Total Votes: $total_votes</b></div>";
}

function poll_show_form_choice_multi($choice_id = "", $votes = "", $id = "", $answer = "")
{
    return "
    <tr><td style='colspan=3'><input type='checkbox' name='choice_{$id}_{$choice_id}' value='1'  />&nbsp;<b>$answer</b></td></tr>";
}

function poll_show_form_choice($choice_id = "", $votes = "", $id = "", $answer = "")
{
    return "
    <tr><td style='nowrap=nowrap'><input type='radio' name='choice[{$id}]' value='$choice_id'  />&nbsp;<strong>$answer</strong></td></tr>";
}

function poll_show_form_question($id = "", $question = "", $choice_html = "")
{
    return "
    <div align='left'>
      <div style='padding:4px;'><span class='postdetails'><strong>{$question}</strong></span></div>
      $choice_html
    </div>";
}

function button_show_voteable()
{
    return "<input class='btn btn-default' type='button' name='viewresult' value='Show Votes'  title='Goto poll voting' onclick=\"go_gadget_vote()\" />";
}

function button_show_results()
{
    return "<input class='btn btn-default' type='button' value='Results' title='Show all poll rsults' onclick=\"go_gadget_show()\" />";
}

function button_vote()
{
    return "<input class='btn btn-default' type='submit' name='submit' value='Vote' title='Poll Vote' />";
}

function button_null_vote()
{
    return "<input class='btn btn-default' type='submit' name='nullvote' value='View Results (Null Vote)' title='View results, but forfeit your vote in this poll' />";
}

?>
