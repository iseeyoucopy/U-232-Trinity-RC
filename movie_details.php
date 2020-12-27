<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php';
require_once INCL_DIR . 'user_functions.php';
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'getpre.php';
dbconn(false);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'navigation_html_functions.php');
$html = array_merge(load_design());
$lang = load_language('global');
global $CURUSER, $INSTALLER09;
if (!isset($_GET['id'])) stderr("Something gone wrong", "Maybe someone is playing football :lol:");
if(isset($_GET['id']) && $_GET['id'] !== '');
$movie_id = $_GET['id'];

$HTMLOUT = '';
$key = '3c387120fb64bef7e859affa4e290d6d';
$ca = curl_init();
curl_setopt($ca, CURLOPT_URL, "http://api.themoviedb.org/3/configuration?api_key=".$key);
curl_setopt($ca, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ca, CURLOPT_HEADER, FALSE);
curl_setopt($ca, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ca);
curl_close($ca);
//var_dump($response);
$config = json_decode($response, true);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.themoviedb.org/3/movie/".$movie_id."?api_key=".$key."&language=en-US");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);
$image = ($config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['poster_path']!='') ? "<img src='" . $config['images']['base_url'] . $config['images']['poster_sizes'][3] . $result['poster_path'] . "'>":"<img src='" .$INSTALLER09['pic_base_url']."/noposter.png'>";
		$date = date_format(date_create($result['release_date']), 'Y');
$HTMLOUT = "<div class='row callout'>";
$HTMLOUT .= "
	<div class='columns large-3'>
	{$image}
	</div>
	<div class='columns large-9'>
	<h6 class='subheader'>{$result['title']} {$date}</h6>
	{$result['overview']}
	</div>";





$HTMLOUT .= "</div>";


echo stdhead("{$result['title']}") . $HTMLOUT . stdfoot();