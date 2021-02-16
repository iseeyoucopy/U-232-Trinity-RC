<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'torrenttable_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php');
$lang = array_merge(load_language('global'));
if (!isset($_GET['id'])) stderr("Something gone weong", "Maybe someone is playing football :lol:");
if(isset($_GET['id']) && $_GET['id'] !== '');
	$show_id = $_GET['id'];
	if (($tvepisode = $cache->get('tv_show_ep_'.$show_id)) === false) {
	$date = date(('Y-m-d'));
	$tvmaze_ep= file_get_contents('https://api.tvmaze.com/episodes/'.$show_id.'');
    $tvepisode = json_decode($tvmaze_ep, true, 512, JSON_THROW_ON_ERROR);
	}	
	$image = ($tvepisode['image']['original'] != '') ? "<img src='".$tvepisode['image']['original']."' style='width:214; height:305px;'>":"<img src='" .$TRINITY20['pic_base_url']."/noposter.png' style='width:214; height:305px;'>";  		
	$unwantedChars = array(',', '!', '?', "'"); // create array with unwanted chars
	$HTMLOUT = $name = $runtime = $premiered = $rating = $official_site = $summary = $number = $schedule_time = $updated = "";
	$name = str_replace($unwantedChars,"",(htmlentities($tvepisode['name'])));
	$url_ep = $tvepisode['url'];
	$season_ep = $tvepisode['season'];
	$number_ep = $tvepisode['number'];
	$episode_airdate = $tvepisode['airdate'];
	$airtime_ep = $tvepisode['airtime'];
	$airstamp_ep = $tvepisode['airstamp'];					
	$runtime_ep = $tvepisode['runtime'];
	$summary = str_replace($unwantedChars,"",(($tvepisode['summary'])));
	$HTMLOUT .= "<div class='grid-x callout'>
		<div class='large-3 columns'>".$image."</div>
		<div class='large-9 columns'>
			<h4><a href='https://nullrefer.com/?{$tvepisode['url']}' target='_blank'>{$name}</a></h4>
			<div class='callout'>{$summary}</div>
					<p><a href='{$url_ep}'>TvMAze</a></p>
		<p>Season : {$season_ep} Episode : {$number_ep}</p>
		<p>Airdate : {$episode_airdate} | AirTime : {$airtime_ep} | Runtime : {$runtime_ep}</p>
		</div>
</div>";	
echo stdhead("{$name}") . $HTMLOUT . stdfoot();
?>
