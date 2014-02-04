<?php
require "core.php";

/** Includes **/
include PSZ_FILE_PATH."inc/class.pagina.php";
include PSZ_FILE_PATH."inc/class.template.php";

/** Initialize Needed Classes **/
global $temp;
$temp = new Template();

$temp->header("Ranking");

$temp->content .= '<h1>Top 20 Players</h1>';
if(isset($C_GET['mods']) && $C_GET['mods'] == 1) {
	$temp->content .= '<p><a href="top20players.php?'.http_build_query(Array('mods' => 0) + $_GET, '', '&').'">Players and V.I.Ps only</a></p>';
} else {
	$temp->content .= '<p><a href="top20players.php?'.http_build_query(Array('mods' => 1) + $_GET, '', '&').'">Include Mods and Admins</a></p>';
}

/** Begin Ranking Table **/
$temp->begin_table();
$ranks = Array(
'<a href="#">#</a>||20||center',
'<a href="ranking.php?'.http_build_query(Array('o' => 'level') + $_GET, '', '&').'">Level</a>||10',
'<a href="ranking.php?'.http_build_query(Array('o' => 'username') + $_GET, '', '&').'">User Name</a>',
'<a href="ranking.php?'.http_build_query(Array('o' => 'gold') + $_GET, '', '&').'">Gold</a>',
'<a href="ranking.php?'.http_build_query(Array('o' => 'coins') + $_GET, '', '&').'">Coins</a>',
'<a href="ranking.php?'.http_build_query(Array('o' => 'server') + $_GET, '', '&').'">Current Server</a>',
'<a href="ranking.php?'.http_build_query(Array('o' => 'area') + $_GET, '', '&').'">Last Located</a>',
);
$temp->create_table_headers($ranks);

/** Switch Query String **/
$o = "";
if(isset($C_GET['o'])) {
	switch($C_GET['o']) {
		case "username":
			$o = $usr['name'];
			break;
		case "coins":
			$o = $usr['coin'];
			break;
		case "gold":
			$o = $usr['gold'];
			break;
		case "server":
			$o = $usr['serv'];
			break;
		case "area":
			$o = $usr['area'];
			break;
		default:
			$o = $usr['levl'];
			break;
	}
} else {
	$o = "iLvl";
}

if(isset($C_GET['mods']) && $C_GET['mods'] == 1) {
	$query = $mysql->query("SELECT {$usr['name']},{$usr['coin']},{$usr['gold']},{$usr['serv']},{$usr['area']},{$usr['levl']} FROM {$tbl['user']} ORDER BY $o DESC LIMIT 20");
} else {
	$query = $mysql->query("SELECT {$usr['name']},{$usr['coin']},{$usr['gold']},{$usr['serv']},{$usr['area']},{$usr['levl']} FROM {$tbl['user']} WHERE {$usr['accs']} < 40 ORDER BY $o DESC LIMIT 20");
}

$i = 1;
while($data = $mysql->fetch($query)) {
	$odd = "";
	if($i % 2) {
		$odd = true;
	}
	$data[$usr['name']] = ucfirst($data[$usr['name']]);
	$ranksp = Array(
		$i.'||10||center',
		$data[$usr['levl']].'||30||center',
		'<a href="profile.php?u='.$data[$usr['name']].'">'.$data[$usr['name']].'</a>||200',
		$data[$usr['gold']],
		$data[$usr['coin']],
		$data[$usr['serv']],
		$data[$usr['area']]
	);
	$temp->create_table_rows($ranksp, $odd);
	++$i;
}
/** End Ranking Table **/
$temp->end_table();

$temp->footer();
$temp->flush();
?>
