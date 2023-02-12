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
if (!defined('IN_TRINITY20_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="'.charset().'">
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    echo $HTMLOUT;
    exit();
}
$subaction = (isset($_GET["subaction"]) ? htmlsafechars($_GET["subaction"]) : (isset($_POST["subaction"]) ? htmlsafechars($_POST["subaction"]) : ''));
$pollid = (isset($_GET["pollid"]) ? (int)$_GET["pollid"] : (isset($_POST["pollid"]) ? (int)$_POST["pollid"] : 0));
$topicid = (isset($_POST["topicid"]) ? (int)$_POST["topicid"] : 0);
if ($subaction == "edit") {
    if (!is_valid_id($pollid)) {
        stderr("Error", "Invalid ID!");
    }
    ($res = sql_query("SELECT pp.*, t.id AS tid FROM postpolls AS pp LEFT JOIN topics AS t ON t.poll_id = pp.id WHERE pp.id=".sqlesc($pollid))) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows == 0) {
        stderr("Error", "No poll found with that ID.");
    }
    $poll = $res->fetch_assoc();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$topicid) {
    $topicid = ($subaction == "edit" ? (int)$poll['tid'] : (int)$_POST["updatetopicid"]);
    $question = htmlsafechars($_POST["question"]);
    $option0 = htmlsafechars($_POST["option0"]);
    $option1 = htmlsafechars($_POST["option1"]);
    $option2 = htmlsafechars($_POST["option2"]);
    $option3 = htmlsafechars($_POST["option3"]);
    $option4 = htmlsafechars($_POST["option4"]);
    $option5 = htmlsafechars($_POST["option5"]);
    $option6 = htmlsafechars($_POST["option6"]);
    $option7 = htmlsafechars($_POST["option7"]);
    $option8 = htmlsafechars($_POST["option8"]);
    $option9 = htmlsafechars($_POST["option9"]);
    $option10 = htmlsafechars($_POST["option10"]);
    $option11 = htmlsafechars($_POST["option11"]);
    $option12 = htmlsafechars($_POST["option12"]);
    $option13 = htmlsafechars($_POST["option13"]);
    $option14 = htmlsafechars($_POST["option14"]);
    $option15 = htmlsafechars($_POST["option15"]);
    $option16 = htmlsafechars($_POST["option16"]);
    $option17 = htmlsafechars($_POST["option17"]);
    $option18 = htmlsafechars($_POST["option18"]);
    $option19 = htmlsafechars($_POST["option19"]);
    $sort = htmlsafechars($_POST["sort"]);
    if (!$question || !$option0 || !$option1) {
        stderr("Error", "Missing form data!");
    }
    if ($subaction == "edit" && is_valid_id($pollid)) {
        sql_query("UPDATE postpolls SET ".
            "question = ".sqlesc($question).", ".
            "option0 = ".sqlesc($option0).", ".
            "option1 = ".sqlesc($option1).", ".
            "option2 = ".sqlesc($option2).", ".
            "option3 = ".sqlesc($option3).", ".
            "option4 = ".sqlesc($option4).", ".
            "option5 = ".sqlesc($option5).", ".
            "option6 = ".sqlesc($option6).", ".
            "option7 = ".sqlesc($option7).", ".
            "option8 = ".sqlesc($option8).", ".
            "option9 = ".sqlesc($option9).", ".
            "option10 = ".sqlesc($option10).", ".
            "option11 = ".sqlesc($option11).", ".
            "option12 = ".sqlesc($option12).", ".
            "option13 = ".sqlesc($option13).", ".
            "option14 = ".sqlesc($option14).", ".
            "option15 = ".sqlesc($option15).", ".
            "option16 = ".sqlesc($option16).", ".
            "option17 = ".sqlesc($option17).", ".
            "option18 = ".sqlesc($option18).", ".
            "option19 = ".sqlesc($option19).", ".
            "sort = ".sqlesc($sort)." ".
            "WHERE id = ".sqlesc((int)$poll["id"])) || sqlerr(__FILE__, __LINE__);
    } else {
        if (!is_valid_id($topicid)) {
            stderr('Error', 'Invalid topic ID!');
        }
        sql_query("INSERT INTO postpolls VALUES(id".
            ", ".sqlesc(TIME_NOW).
            ", ".sqlesc($question).
            ", ".sqlesc($option0).
            ", ".sqlesc($option1).
            ", ".sqlesc($option2).
            ", ".sqlesc($option3).
            ", ".sqlesc($option4).
            ", ".sqlesc($option5).
            ", ".sqlesc($option6).
            ", ".sqlesc($option7).
            ", ".sqlesc($option8).
            ", ".sqlesc($option9).
            ", ".sqlesc($option10).
            ", ".sqlesc($option11).
            ", ".sqlesc($option12).
            ", ".sqlesc($option13).
            ", ".sqlesc($option14).
            ", ".sqlesc($option15).
            ", ".sqlesc($option16).
            ", ".sqlesc($option17).
            ", ".sqlesc($option18).
            ", ".sqlesc($option19).
            ", ".sqlesc($sort).")") || sqlerr(__FILE__, __LINE__);

        $pollnum = $mysqli->insert_id;
        sql_query("UPDATE topics SET poll_id=".sqlesc($pollnum)." WHERE id=".sqlesc($topicid)) || sqlerr(__FILE__, __LINE__);
    }
    header("Location: {$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid");
    exit();
}
$HTMLOUT .= '<div class="card">
<div class="card-divider">Add Polls in Forums</div>
<div class="card-section">';
if ($subaction == "edit") {
    $HTMLOUT .= "<h1>Edit poll</h1>";
}
$HTMLOUT .= "<form method='post' action='forums.php'>
   <input type='hidden' name='action' value='".$action."'>
	<input type='hidden' name='subaction' value='".$subaction."'>
	<input type='hidden' name='updatetopicid' value='".$topicid."'>";
if ($subaction == "edit") {
    $HTMLOUT .= "<input type='hidden' name='pollid' value='".(int)$poll["id"]."'>";
}
$HTMLOUT .= '
<div class="input-group">
    <span class="input-group-label">Question </span>
    <textarea class="input-group-field" name="question">'.($subaction == 'edit' ? htmlsafechars($poll['question']) : '').'</textarea>
</div>
<div class="grid-x grid-margin-x">
    <div class="cell large-6">
        <div class="input-group">
            <span class="input-group-label">Option 1</span>
            <input  class="input-group-field" name="option0" value="'.($subaction == 'edit' ? htmlsafechars($poll['option0']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 2</span>
            <input  class="input-group-field" name="option1" value="'.($subaction == 'edit' ? htmlsafechars($poll['option1']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 3</span>
            <input  class="input-group-field" name="option2" value="'.($subaction == 'edit' ? htmlsafechars($poll['option2']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 4</span>
            <input  class="input-group-field" name="option3" value="'.($subaction == 'edit' ? htmlsafechars($poll['option3']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 5</span>
            <input  class="input-group-field" name="option4" value="'.($subaction == 'edit' ? htmlsafechars($poll['option4']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 6</span>
            <input  class="input-group-field" name="option5" value="'.($subaction == 'edit' ? htmlsafechars($poll['option5']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 7</span>
            <input  class="input-group-field" name="option6" value="'.($subaction == 'edit' ? htmlsafechars($poll['option6']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 8</span>
            <input  class="input-group-field" name="option7" value="'.($subaction == 'edit' ? htmlsafechars($poll['option7']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 9</span>
            <input  class="input-group-field" name="option8" value="'.($subaction == 'edit' ? htmlsafechars($poll['option8']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 10</span>
            <input  class="input-group-field" name="option9" value="'.($subaction == 'edit' ? htmlsafechars($poll['option9']) : '').'">
        </div>
    </div>
    <div class="cell large-6">
        <div class="input-group">
            <span class="input-group-label">Option 11</span>
            <input  class="input-group-field" name="option10" value="'.($subaction == 'edit' ? htmlsafechars($poll['option10']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 12</span>
            <input  class="input-group-field" name="option11" value="'.($subaction == 'edit' ? htmlsafechars($poll['option11']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 13</span>
            <input  class="input-group-field" name="option12" value="'.($subaction == 'edit' ? htmlsafechars($poll['option12']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 14</span>
            <input  class="input-group-field" name="option13" value="'.($subaction == 'edit' ? htmlsafechars($poll['option13']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 15</span>
            <input  class="input-group-field" name="option14" value="'.($subaction == 'edit' ? htmlsafechars($poll['option14']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 16</span>
            <input  class="input-group-field" name="option15" value="'.($subaction == 'edit' ? htmlsafechars($poll['option15']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 17</span>
            <input  class="input-group-field" name="option16" value="'.($subaction == 'edit' ? htmlsafechars($poll['option16']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 18</span>
            <input  class="input-group-field" name="option17" value="'.($subaction == 'edit' ? htmlsafechars($poll['option17']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 19</span>
            <input  class="input-group-field" name="option18" value="'.($subaction == 'edit' ? htmlsafechars($poll['option18']) : '').'">
        </div>
        <div class="input-group">
            <span class="input-group-label">Option 20</span>
            <input  class="input-group-field" name="option19" value="'.($subaction == 'edit' ? htmlsafechars($poll['option19']) : '').'">
        </div>
    </div>
    <fieldset class="large-12 cell">
        <legend>Sort</legend>
        <input type="radio" name="sort" value="yes" '.($subaction == 'edit' ? ($poll['sort'] != 'no' ? ' checked="checked"' : '') : '').'> Yes
        <input type="radio" name="sort" value="no" '.($subaction == 'edit' ? ($poll['sort'] != 'no' ? ' checked="checked"' : '') : '').'> No
    </fieldset>
    <input class="button float-center" type="submit" value="'.($pollid !== 0 ? 'Edit poll' : 'Create poll').'">
    </form>
</div>
</div></div>';
echo stdhead("Polls").$HTMLOUT.stdfoot($stdfoot);
