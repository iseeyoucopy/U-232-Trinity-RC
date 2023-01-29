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
require_once __DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php';
require_once INCL_DIR.'user_functions.php';
require_once(INCL_DIR.'bbcode_functions.php');
require_once(INCL_DIR.'pager_functions.php');
require_once INCL_DIR.'html_functions.php';
require_once INCL_DIR.'getpre.php';
dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].''.DIRECTORY_SEPARATOR.'html_functions'.DIRECTORY_SEPARATOR.'global_html_functions.php');
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].''.DIRECTORY_SEPARATOR.'html_functions'.DIRECTORY_SEPARATOR.'navigation_html_functions.php');
$lang = load_language('global');
$HTMLOUT = '';
$HTMLOUT .= '<style>
.checked {
    color: orange;
}
</style>';
$HTMLOUT .= "
	<ul class='tabs'>
                <li class='tabs-title'><a href='tv_guide.php?country=US'>USA TV Schedule</a></li>
                <li class='tabs-title'><a href='tv_guide.php?country=GB'>UK TV Schedule</a></li>
                <li class='tabs-title'><a href='tv_guide.php?country=FR'>France TV Schedule</a></li>
                <li class='tabs-title'><a href='tv_guide.php?country=IE'>Ireland TV Schedule</a></li>
                <li class='tabs-title'><a href='tv_guide.php?country=SE'>Sweden TV Schedule</a></li>
                <li class='tabs-title'><a href='tv_guide.php?country=DE'>Germany TV Schedule</a></li>
		</ul>
";
$lcountry = (isset($_REQUEST['country'])) ? $_REQUEST["country"] : "US";
if (($tvsched = $cache->get($cache_keys['schedule_new'].$lcountry)) === false) {
    $date = date(('Y-m-d'));
    $tvmaze = file_get_contents('https://api.tvmaze.com/schedule?country='.$lcountry.'&date='.$date);
    $tvsched = json_decode($tvmaze, true, 512, JSON_THROW_ON_ERROR);
    if ((is_countable($tvsched) ? count($tvsched) : 0) > 0) {
        $cache->set($cache_keys['schedule_new'].$lcountry, $tvsched, 60 * 60);
    }
}
$dcountry = "";
switch ($lcountry) {
    case "US":
        $dcountry = "Usa";
        break;
    case "GP":
        $dcountry = "United Kingdom";
        break;
    case "FR":
        $dcountry = "France";
        break;
    case "IE":
        $dcountry = "Ireland";
        break;
    case "SE":
        $dcountry = "Sweden";
        break;
    case "DE":
        $dcountry = "Germany";
        break;
}
$HTMLOUT .= "<div class='card'><div class='card-divider'><h4 class='subheader'>Upcoming ".$dcountry." TV Schedule</h4></div><div class='card-section'><div class='grid-x grid-padding-x small-up-1 medium-up-3 large-up-4' data-equalizer data-equalizer data-equalize-by-row='true'  id='tv_equal'>";
foreach ($tvsched as $key => $item) {
    $image = $item['show']['image']['original'] ?? '';
    $airimg = ($image != '') ? "<img src='".$item['show']['image']['original']."'></img>" : "<img src='".$TRINITY20['pic_base_url']."/noposter.png'></img>";
    //episode info
    $unwantedChars = [',', '!', '?', "'"]; // create array with unwanted chars
    $season = "";
    $number = "";
    $airdate = "";
    $airtime = "";
    $airstamp = "";
    $runtime = "";
    $episodeSummary = "";
    $itemName = "";
    $itemType = "";
    $itemLanguage = "";
    $itemPremiered = "";
    $itemAverageRating = "";
    $itemGenre = "";
    $image = "";
    $itemSummary = "";
    //$episodeID = ($item['id']);
    //$item['id'] = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
    //$tvmaze_shows = sprintf('http://api.tvmaze.com/shows/%d', $episodeID);
    //$tvmaze_array = json_decode(file_get_contents($tvmaze_shows), true);
    $episodeName = ($item['name']);
    $season = $item['season'];
    $number = $item['number'];
    $airdate = $item['airdate'];
    $airtime = $item['airtime'];
    $airstamp = $item['airstamp'];
    $runtime = $item['runtime'];
    $network = $item['show']['network']['name'] ?? '';
    $episodeSummary = str_replace($unwantedChars, "", (($item['show']['summary'])));

    //show info
    $itemName = str_replace($unwantedChars, "", (htmlentities($item['show']['name'])));
    $subtitleLink = "https://www.opensubtitles.org/en/search2/sublanguageid-eng/searchonlytvseries-on/moviename-".str_replace(" ", "+", $itemName);
    $itemType = $item['show']['type'];
    $itemLanguage = $item['show']['language'];
    $itemPremiered = $item['show']['premiered'];
    $itemAverageRating = $item['show']['rating']['average'];
    //some genres are blank
    $showGenre = "";
    foreach ($item['show']['genres'] as $key => $genre) {
        $showGenre = $showGenre." <a class='float-left' href='browse.php?search={$genre}&searchin=genre&incldead=0' target='_blank'>".$genre." | </a>";
    }
    $itemSummary = str_replace($unwantedChars, "", (($item['show']['summary'])));
    $episodeName = str_replace($unwantedChars, "", (htmlentities($item['name'])));
    $HTMLOUT .= "<div class='cell callout' data-equalizer-watch><a href='tv_show.php?id={$item['show']['id']}'><p style='text-overflow: ellipsis; overflow: hidden; white-space: nowrap;'>{$item['show']['name']}</p>".$airimg."</a>
		<a data-open='showsModal{$item['show']['id']}' class='tiny button float-right'>Read More</a></div>";
    $HTMLOUT .= "<div class='large reveal' id='showsModal{$item['show']['id']}' data-reveal>
				<div class='grid-x grid-padding-x'>
		<div class='large-2 cell'>".$airimg."</div>
		<div class='large-10 cell'>
			<h4>{$item['show']['name']}</h4>";
    $HTMLOUT .= "<b>{$showGenre}</b> <p>Airs on {$network}</p>
			<a class='float-right' href='browse.php?search={$item['show']['name']} S".($season < 10 ? '0'.$season : $season)."E".($number < 10 ? '0'.$number : $number)."&searchin=title'><i class='fas fa-search'></i></a>";
    $rating = $itemAverageRating;
    $x = 1;
    for ($x = 1; $x <= $itemAverageRating; $x++) {
        $HTMLOUT .= '<span class="fas fa-star checked"></span>';
        if ($x++ == 5) {
            break;
        }
    }
    if (strpos((string)$itemAverageRating, '.')) {
        $HTMLOUT .= '<span class="fas fa-star-half"></span>';
        $x++;
    }
    while ($x < 5) {
        $HTMLOUT .= '<span class="far fa-star"></span>';
        $x++;
    }
    $HTMLOUT .= " <a class='label' href='tv_show.php?id={$item['show']['id']}'>List of seasons and episodes</a>
		  <div class='callout'>
			<p><strong style='color:#fe7600;'>Season: {$item['season']}</strong>
		<strong style='color:#080;'>Episode: {$item['number']}</strong></p>
				<strong>On this Episode</strong>{$episodeSummary}
		  </div>
		  <a class='button float-right' type='button'>Follow</a>
		   </div>
		<button class='close-button' data-close aria-label='Close modal' type='button'>
    <span aria-hidden='true'>&times;</span>
  </button>";
    $HTMLOUT .= "</div></div>";
}
$HTMLOUT .= "</div></div>
<nav aria-label='Pagination'>
  <ul class='pagination'>
    <li class='pagination-previous disabled'>Previous <span class='show-for-sr'>page</span></li>
    <li class='current'><span class='show-for-sr'>You're on page</span> 1</li>
    <li><a href='#' aria-label='Page 2'>2</a></li>
    <li><a href='#' aria-label='Page 3'>3</a></li>
    <li><a href='#' aria-label='Page 4'>4</a></li>
    <li class='ellipsis' aria-hidden='true'></li>
    <li><a href='#' aria-label='Page 12'>12</a></li>
    <li><a href='#' aria-label='Page 13'>13</a></li>
    <li class='pagination-next'><a href='#' aria-label='Next page'>Next <span class='show-for-sr'>page</span></a></li>
  </ul>
</nav></div>";
echo stdhead("Upcoming TV Episodes").$HTMLOUT.stdfoot();
